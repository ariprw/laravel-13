<?php
if (!function_exists('ic')) {
    function ic($name='', $class='') {
        $icons = [
            'logo' => '',
            'house' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>',
            'log-out' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out preview-icon"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>',
            'user-round' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round preview-icon"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>',
            'users-round' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round preview-icon"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>',
            'building-complex' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building-complex preview-icon"><path d="M10 12h4"/><path d="M10 8h4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"/><path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/></svg>',
            'key-round' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round preview-icon"><path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"/><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/></svg>',
            'info' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info preview-icon"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
            'edit' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen preview-icon"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>',
            'delete' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash preview-icon"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>',
            'print' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer preview-icon"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>',
            'logs' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-logs preview-icon"><path d="M3 5h1"/><path d="M3 12h1"/><path d="M3 19h1"/><path d="M8 5h1"/><path d="M8 12h1"/><path d="M8 19h1"/><path d="M13 5h8"/><path d="M13 12h8"/><path d="M13 19h8"/></svg>',
        ];

        if (!isset($icons[$name])) {
            return '';
        }

        $svg = $icons[$name];

        if ($class !== '') {
            $svg = preg_replace(
                '/<svg\b/',
                '<svg class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"',
                $svg,
                1
            );
        }

        return $svg;
    }

    function formSubmitJs(): string {
        return <<<'HTML'
        <script>
        document.querySelectorAll('.formSubmit').forEach(form => {
            form.addEventListener('submit', function () {
                const button = this.querySelector('[type="submit"]');

                if (!button) return;

                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
            });
        });
        </script>
        HTML;
    }
    
    function customSelect($data=[]) {
        $dark = isset($data['dark']) ? (($data['dark'] === true) ? true : false) : false;
        $bgColor = 'bg-white';
        $textColor = 'text-black';
        $hoverBgColor = 'hover:bg-slate-200';
        $inputBgColor = 'bg-slate-100 '.$textColor;
        $borderColor = 'border-slate-300';

        if ($dark) {
            $bgColor = 'bg-dark';
            $textColor = 'text-white';
            $hoverBgColor = 'hover:bg-black';
            $inputBgColor = 'bg-dark '.$textColor;
            $borderColor = 'border-slate-300';
        }

        return '
        <script>
        function initCustomSelect(minListOption=4) {
            const customSelects = document.getElementsByClassName("customSelect");

            Array.from(customSelects).forEach(customSelect => {
                const selectElement = customSelect.getElementsByTagName("select")[0];

                if (!selectElement) return;
                if (!selectElement.options[selectElement.selectedIndex]) return;
                
                const optionsLength = selectElement.length;

                customSelect.classList.add(...["relative", "select-none"]);
                selectElement.classList.add("sr-only");
                selectElement.setAttribute("tabindex", "-1");
                selectElement.addEventListener("keydown", function(e) {
                    if (e.code === "Space" || e.key === " ") {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });

                const selectedDiv = document.createElement("div");
                selectedDiv.className = "selectSelected grid grid-cols-10 items-center '.$bgColor.' text-slate-900 text-sm border border-solid '.$borderColor.' rounded-xl my-1 px-4 py-2.5 relative cursor-pointer select-none";

                const selectedContent = document.createElement("div");
                selectedContent.className = "selectedContent col-span-9";
                selectedContent.innerHTML = selectElement.options[selectElement.selectedIndex].innerHTML;

                const arrow = document.createElement("div");
                arrow.className = "selectArrow col-span-1 absolute right-3 transform duration-200 ease-in-out";
                arrow.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>`;

                selectedDiv.append(selectedContent, arrow);
                customSelect.appendChild(selectedDiv);

                const itemsDiv = document.createElement("div");
                itemsDiv.className = "selectItems absolute w-full min-h-min max-h-52 top-full right-0 left-0 z-10 '.$bgColor.' border border-solid border-t-none '.$borderColor.' rounded-xl rounded-t-none overflow-y-auto -mt-1 hidden";

                const itemsDivSearch = document.createElement("span");
                itemsDivSearch.setAttribute("class", "block p-1");

                const searchInput = document.createElement("input");
                searchInput.setAttribute("type", "text");
                searchInput.setAttribute("placeholder", "Cari..");
                searchInput.setAttribute("class", "selectSearch block '.$inputBgColor.' border border-solid '.$borderColor.' focus:border-indigo-600 rounded-xl p-2 text-sm w-full outline-none");

                if (optionsLength <= minListOption) {
                    itemsDivSearch.classList.add("hidden");
                } else {
                    itemsDivSearch.classList.remove("hidden");
                }

                itemsDivSearch.appendChild(searchInput);
                itemsDiv.appendChild(itemsDivSearch);

                for (let i = 0; i < optionsLength; i++) {
                    const optionDiv = document.createElement("div");
                    optionDiv.innerHTML = selectElement.options[i].innerHTML;
                    optionDiv.dataset.value = selectElement.options[i].value;
                    optionDiv.className = "cursor-pointer '.$bgColor.' '.$hoverBgColor.' '.$bgColor.' text-sm p-2";

                    optionDiv.addEventListener("click", function() {
                        const select = this.parentNode.parentNode.getElementsByTagName("select")[0];
                        const selectedDiv = this.parentNode.previousSibling;
                        let optionIndex;

                        for (let j = 0; j < select.length; j++) {
                            if (select.options[j].value === this.dataset.value) {
                                optionIndex = j;
                                break;
                            }
                        }

                        select.selectedIndex = optionIndex;
                        selectedDiv.querySelector(".selectedContent").innerHTML = this.innerHTML;
                        selectedDiv.classList.replace("text-slate-500", "'.$textColor.'");

                        Array.from(this.parentNode.getElementsByClassName("sameAsSelected")).forEach(item => {
                            item.classList.remove(...["font-bold", "sameAsSelected"]);
                        });

                        this.classList.add(...["font-bold", "sameAsSelected"]);
                        selectedDiv.click();
                    });

                    itemsDiv.appendChild(optionDiv);
                }

                customSelect.appendChild(itemsDiv);

                selectedDiv.addEventListener("click", function(e) {
                    e.stopPropagation();
                    closeAllSelect(this);
                    this.nextSibling.classList.toggle("hidden");
                    this.classList.toggle("selectArrowActive");
                    this.classList.toggle("rounded-b-none");
                    this.querySelector(".selectArrow").classList.toggle("rotate-180");
                });

                searchInput.addEventListener("click", function(e) {
                    e.stopPropagation();
                });

                searchInput.addEventListener("keypress", function(e) {
                    if (e.keyCode === 13 || e.key === "Enter") {
                        e.preventDefault();
                    }
                });

                searchInput.addEventListener("keyup", function(e) {
                    const filter = this.value.toLowerCase();
                    const items = itemsDiv.querySelectorAll(":scope > div[data-value]");

                    Array.from(items).forEach(item => {
                        if (item.innerText.toLowerCase().includes(filter)) {
                            item.classList.remove("hidden");
                        } else {
                            item.classList.add("hidden");
                        }
                    });
                });

                searchInput.addEventListener("blur", function(e) {
                    this.value = "";
                });

                const listSelectSelected = customSelect.querySelectorAll(".selectSelected");
                const listSelectItems = customSelect.querySelectorAll(".selectItems");

                Array.from(listSelectSelected).forEach((itemSelectSelected, ke) => {
                    if (ke !== (listSelectSelected.length - 1)) {
                        itemSelectSelected.remove();
                    }
                });

                Array.from(listSelectItems).forEach((itemSelectItems, ke) => {
                    if (ke !== (listSelectItems.length - 1)) {
                        itemSelectItems.remove();
                    }
                });
            });
        }

        function closeAllSelect(element) {
            const selectItems = document.getElementsByClassName("selectItems");
            const selectSelected = document.getElementsByClassName("selectSelected");
            const itemsLength = selectItems.length;
            const selectedLength = selectSelected.length;
            const openIndices = [];

            Array.from(selectSelected).forEach((selected, index) => {
                if (element === selected) {
                    openIndices.push(index);
                } else {
                    selected.classList.remove("selectArrowActive");
                    selected.classList.remove("rounded-b-none");
                    selected.querySelector(".selectArrow").classList.remove("rotate-180");
                }
            });

            Array.from(selectItems).forEach((items, index) => {
                Array.from(items.getElementsByTagName("div")).forEach(item => {
                    item.classList.remove("hidden");
                });

                if (!openIndices.includes(index)) {
                    items.classList.add("hidden");
                }
            });
        }

        function openLastCustomSelect(v="") {
            if (!lastTypedCustomSelect || lastTypedCustomSelect.offsetParent === null) {
                return;
            }

            const selectedDiv = lastTypedCustomSelect.querySelector(".selectSelected");
            const itemsDiv = lastTypedCustomSelect.querySelector(".selectItems");
            const itemsSearch = itemsDiv.querySelector(".selectSearch");

            if (selectedDiv && itemsDiv && itemsSearch) {
                selectedDiv.classList.add("selectArrowActive");
                selectedDiv.querySelector(".selectArrow").classList.add("rotate-180");
                itemsDiv.classList.remove("hidden");
                itemsSearch.value=v;
                itemsSearch.focus();
            }
        }

        document.addEventListener("DOMContentLoaded", () => initCustomSelect());
        document.addEventListener("click", () => closeAllSelect(null));
        lastTypedCustomSelect = null;
        </script>';
    }
    
    function ribuan($ribuan) {
        $ribuan = preg_replace('/[^-0-9]+/', '', $ribuan);

        if (empty($ribuan)) {
            $hasil = '0';
        } else {
            $hasil = number_format($ribuan, 0, ',', '.');
        }
        
        return $hasil;
    }
}
