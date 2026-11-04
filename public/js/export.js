const notifExport = document.querySelector('.notifExport');

const cssSuccess = 'bg-green-100 text-green-800 text-sm lg:text-base mb-5 p-4 rounded-xl';
const cssError = 'bg-red-100 text-red-800 text-sm lg:text-base mb-5 p-4 rounded-xl';
const cssWarning = 'mt-1 text-xs lg:text-sm text-red-600 italic';

const category = document.querySelector('select[name="category"]');
const customSelectCategory = category.closest('.customSelect');

const createNotifCategory = document.createElement('div');
createNotifCategory.className = 'notifCategory ' + cssWarning;
customSelectCategory.closest('.col-span-7').appendChild(createNotifCategory);

const notifCategory = document.querySelector('.notifCategory');

const contentStartDate = document.querySelector('.contentStartDate');
const startDate = document.querySelector('input[name="startDate"]');

const createNotifStartDate = document.createElement('div');
createNotifStartDate.className = 'notifStartDate ' + cssWarning;
startDate.closest('.col-span-7').appendChild(createNotifStartDate);

const notifStartDate = document.querySelector('.notifStartDate');

const contentEndDate = document.querySelector('.contentEndDate');
const endDate = document.querySelector('input[name="endDate"]');

const createNotifEndDate = document.createElement('div');
createNotifEndDate.className = 'notifEndDate ' + cssWarning;
endDate.closest('.col-span-7').appendChild(createNotifEndDate);

const notifEndDate = document.querySelector('.notifEndDate');

const buttonExport = document.querySelector('.buttonExport');
const token = document.querySelector('input[name="_token"]').value;
const urlBatch = urlExport + '/{requestId}/batch';

customSelectCategory.addEventListener('click', () => {
    notifCategory.textContent = '';
    notifStartDate.textContent = '';
    notifEndDate.textContent = '';

    if (category.value === 'created_at') {
        contentStartDate.classList.remove('hidden');
        contentEndDate.classList.remove('hidden');
    } else {
        contentStartDate.classList.add('hidden');
        contentEndDate.classList.add('hidden');
        startDate.value = '';
        endDate.value = '';
    }
});

async function requestJson(url, body) {
    const response = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(body)
    });

    let data;

    try {
        data = await response.json();
    } catch {
        throw new Error('Respons server tidak valid.');
    }

    if (!response.ok) {
        const error = new Error(data.message || `HTTP ${response.status}`);
        error.status = response.status;
        error.errors = data.errors || null;
        throw error;
    }

    return data;
}

async function getExportData(category, startDate, endDate) {
    return requestJson(urlExport, {
        module: module,
        category: category,
        startDate: startDate || null,
        endDate: endDate || null
    });
}

async function exportData(requestId, start, take) {
    const url = urlBatch.replace('{requestId}', encodeURIComponent(requestId));

    return requestJson(url, {
        start: start,
        take: take
    });
}

async function cleanupData(requestId) {
    try {
        const response = await fetch(`${urlExport}/${requestId}/cleanup`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return true;
    } catch (error) {
        return false;
    }
}

function showExportError(error) {
    notifExport.className = cssError;

    if (error.errors) {
        const messages = Object.values(error.errors).flat();
        notifExport.textContent = messages.join(' ');
    } else {
        notifExport.textContent = error.message || 'Terjadi kesalahan saat export.';
    }
}

function showValidationErrors(errors) {
    if (!errors) return;

    if (errors.category) {
        notifCategory.textContent = errors.category[0];
    }

    if (errors.startDate) {
        notifStartDate.textContent = errors.startDate[0];
    }

    if (errors.endDate) {
        notifEndDate.textContent = errors.endDate[0];
    }

    if (errors.module) {
        showExportError({
            errors: errors
        });
    }
}

buttonExport.addEventListener('click', async () => {
    if (buttonExport.dataset.downloading === 'true') {
        return;
    }

    notifExport.className = '';
    notifExport.textContent = '';
    notifCategory.textContent = '';
    notifStartDate.textContent = '';
    notifEndDate.textContent = '';
    const valueCategory = category.value.trim();
    const valueStartDate = startDate.value.trim();
    const valueEndDate = endDate.value.trim();

    if (valueCategory === '') {
        category.focus();
        notifCategory.textContent = 'Wajib diisi';
        return;
    }

    if (valueCategory === 'created_at') {
        if (valueStartDate === '') {
            startDate.focus();
            notifStartDate.textContent = 'Wajib diisi';
            return;
        }

        if (valueEndDate === '') {
            endDate.focus();
            notifEndDate.textContent = 'Wajib diisi';
            return;
        }

        if (valueEndDate < valueStartDate) {
            endDate.focus();
            notifEndDate.textContent = 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal';
            return;
        }
    }

    buttonExport.dataset.downloading = 'true';
    buttonExport.disabled = true;
    buttonExport.innerText = 'Memproses Export...';
    const allData = [];

    try {
        const result = await getExportData(valueCategory, valueStartDate, valueEndDate);
        const job = result.data;

        if (!job || !job.requestId) {
            throw new Error('Gagal membuat sesi export.');
        }

        let start = Number(job.start);
        const take = Number(job.take);
        const total = Number(job.total);

        if (!Number.isInteger(start) || !Number.isInteger(take) || !Number.isInteger(total) || start < 0 || take < 1 || total < 1) {
            throw new Error('Informasi export tidak valid.');
        }

        notifExport.className = cssSuccess;
        notifExport.textContent = 'Proses export ' + formatNumber(total) + ' data';
        let finished = false;

        while (start < total && !finished) {
            const datas = await exportData(job.requestId, start, take);

            if (!datas || datas.requestId !== job.requestId) {
                throw new Error('Respons batch tidak valid.');
            }

            if (!Number.isInteger(datas.take) || datas.take < 1 || !datas.downloadUrl) {
                throw new Error('Data batch tidak valid.');
            }

            allData.push(datas.downloadUrl);

            if (datas.done === true) {
                if (datas.nextStart !== null) {
                    throw new Error('Status batch tidak valid.');
                }

                finished = true;
                break;
            }

            const nextStart = Number(datas.nextStart);

            if (!Number.isInteger(nextStart) || nextStart <= start || nextStart >= total) {
                throw new Error('Posisi batch tidak valid.');
            }

            start = nextStart;
            const percent = Math.min(100, Math.floor((start / total) * 100));
            buttonExport.innerText = percent + '% Memproses...';
            notifExport.textContent = 'Memproses ' + formatNumber(start) + ' dari ' + formatNumber(total) + ' data';

            await delay(500);
        }

        if (!finished) {
            throw new Error('Proses batch belum selesai.');
        }

        if (allData.length === 0) {
            throw new Error('Tidak ada file batch yang berhasil dibuat.');
        }

        buttonExport.innerText = 'Menyiapkan Excel...';
        notifExport.className = cssSuccess;
        notifExport.textContent = 'Menyiapkan file Excel ' + formatNumber(total) + ' data';
        
        await mergeExcelJs(allData, {
            'requestId': job.requestId,
            'total': total,
            'category': valueCategory,
            'startDate': valueStartDate,
            'endDate': valueEndDate
        });

        await cleanupData(job.requestId);
    } catch (error) {
        if (error.errors) {
            showValidationErrors(error.errors);
        }

        showExportError(error);
    } finally {
        refreshButtonExport();
    }
});

async function mergeExcelJs(arrayUrl, options = {}) {
    const {
        requestId,
        total,
        category,
        startDate,
        endDate
    } = options;

    try {
        const timestamp = generateTimestamp();
        let fileNameFinal = '';
        let fileNameCategory = '(Semua)';

        if (category === 'created_at') {
            fileNameCategory = `(` + `${formatDate(startDate)} - ${formatDate(endDate)}` + `)`;
        }

        fileNameFinal = `${title} ${fileNameCategory} - ${timestamp}${requestId.substr(0, 5)}.xlsx`;

        const wb = new ExcelJS.Workbook();
        const sheetParent = wb.addWorksheet('Data');
        let headerParentSet = false;
        let parentHeaders = [];
        let childKeysGlobal = [];
        const sheetMap = {};
        const parentRowMap = {};
        const childRowMap = {};

        for (const itemUrl of arrayUrl) {
            const response = await fetch(itemUrl, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil file JSON batch.');
            }

            const jsonBatch = await response.json();

            if (!Array.isArray(jsonBatch) || jsonBatch.length === 0) {
                throw new Error('Format JSON batch tidak valid.');
            }

            for (const obj of jsonBatch) {
                if (!obj || typeof obj !== 'object' || Array.isArray(obj)) {
                    throw new Error('Format data parent tidak valid.');
                }

                const childKeys = Object.keys(obj).filter(key => Array.isArray(obj[key]) && (obj[key].length === 0 || obj[key].every(item => item !== null && typeof item === 'object' && !Array.isArray(item))));
                const parentObj = {
                    ...obj
                };

                childKeys.forEach(key => {
                    delete parentObj[key];
                });

                if (!headerParentSet) {
                    parentHeaders = Object.keys(parentObj);
                    childKeysGlobal = [...childKeys];

                    const headers = [...parentHeaders, ...childKeysGlobal.map(key => `Detail ${key}`)];

                    sheetParent.addRow(headers);

                    styleHeader(sheetParent.getRow(1));

                    sheetParent.views = [{
                        state: 'frozen',
                        ySplit: 1
                    }];

                    headerParentSet = true;
                }

                if (Object.keys(parentObj).join('|') !== parentHeaders.join('|')) {
                    throw new Error('Struktur kolom parent tidak konsisten.');
                }

                const parentRow = sheetParent.addRow(parentHeaders.map(key => parentObj[key]));

                parentRow.eachCell(cell => {
                    cell.alignment = {
                        wrapText: true,
                        vertical: 'middle'
                    };
                    cell.numFmt = '@';
                });

                const parentId = parentObj[parentHeaders[0]];

                if (parentId === null || parentId === undefined || parentId === '') {
                    throw new Error('ID parent tidak ditemukan.');
                }

                const parentKey = String(parentId);

                if (Object.prototype.hasOwnProperty.call(parentRowMap, parentKey)) {
                    throw new Error(
                        `ID parent "${parentKey}" duplikat pada baris ${parentRowMap[parentKey]} dan ${parentRow.number}.`
                        );
                }

                parentRowMap[parentKey] = parentRow.number;

                childKeys.forEach((key, idx) => {
                    const arr = obj[key];

                    if (!arr.length) return;

                    if (!sheetMap[key]) {
                        const ws = wb.addWorksheet(key);

                        sheetMap[key] = ws;
                        childRowMap[key] = {};

                        const childHeaders = ['Parent ID', ...Object.keys(arr[0])];

                        ws.addRow(childHeaders);

                        styleHeader(ws.getRow(1));

                        ws.views = [{
                            state: 'frozen',
                            ySplit: 1
                        }];
                    }

                    const ws = sheetMap[key];
                    const expectedHeaders = ws.getRow(1).values.slice(2);
                    const currentHeaders = Object.keys(arr[0]);

                    if (expectedHeaders.join('|') !== currentHeaders.join('|')) {
                        throw new Error(`Struktur kolom detail ${key} tidak konsisten.`);
                    }

                    arr.forEach(item => {
                        const row = ws.addRow([parentId, ...currentHeaders.map(field => item[field])]);

                        row.eachCell(cell => {
                            cell.alignment = {
                                wrapText: true,
                                vertical: 'middle'
                            };

                            cell.numFmt = '@';
                        });

                        row.getCell(1).value = {
                            text: String(parentId),
                            hyperlink: `#'Data'!A${parentRowMap[parentKey]}`
                        };

                        row.getCell(1).font = {
                            color: {
                                argb: '0000FF'
                            },
                            underline: true
                        };

                        if (!childRowMap[key][parentKey]) {
                            childRowMap[key][parentKey] = row.number;
                        }
                    });

                    const parentCell = parentRow.getCell(parentHeaders.length + idx + 1);

                    if (childRowMap[key][parentKey]) {
                        parentCell.value = {
                            text: `Lihat ${key}`,
                            hyperlink: `#'${key}'!A${childRowMap[key][parentKey]}`
                        };

                        parentCell.font = {
                            color: {
                                argb: '0000FF'
                            },
                            underline: true
                        };
                    }
                });
            }
        }

        wb.worksheets.forEach(ws => {
            ws.columns.forEach(col => {
                let max = 10;

                col.eachCell({
                    includeEmpty: true
                }, cell => {
                    const value = cell.value;
                    let text = '';

                    if (value !== null && value !== undefined) {
                        if (typeof value === 'object') {
                            text = String(value.text ?? '');
                        } else {
                            text = String(value);
                        }
                    }

                    max = Math.max(max, text.length);
                });

                col.width = Math.min(max + 5, 60);
            });
        });

        const buffer = await wb.xlsx.writeBuffer();
        const blob = new Blob([buffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });

        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileNameFinal;
        document.body.appendChild(a);
        a.click();
        a.remove();

        setTimeout(() => {
            URL.revokeObjectURL(url);
        }, 1000);

        notifExport.className = cssSuccess;
        notifExport.textContent = 'File telah terdownload ' + formatNumber(total) + ' data: ' + fileNameFinal;
    } catch (err) {
        throw new Error('Gagal membuat file Excel: ' + err.message);
    }
}

function styleHeader(row) {
    row.eachCell(cell => {
        cell.font = {
            bold: true
        };

        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: {
                argb: 'd4edda'
            }
        };

        cell.alignment = {
            vertical: 'middle',
            horizontal: 'center'
        };
    });

    row.height = 25;
}

function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function refreshButtonExport() {
    buttonExport.dataset.downloading = 'false';
    buttonExport.disabled = false;
    buttonExport.innerText = 'Export';
}

function generateTimestamp() {
    const now = new Date();
    return (now.getFullYear() + String(now.getMonth() + 1).padStart(2, '0') + String(now.getDate()).padStart(2, '0') + String(now.getHours()).padStart(2, '0') + String(now.getMinutes()).padStart(2, '0') + String(now.getSeconds()).padStart(2, '0'));
}

function formatDate(date) {
    return new Date(date + 'T00:00:00').toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

function formatNumber(value) {
    return Number(value).toLocaleString('id-ID');
}

category.value = '';
startDate.value = '';
endDate.value = '';
contentStartDate.classList.add('hidden');
contentEndDate.classList.add('hidden');