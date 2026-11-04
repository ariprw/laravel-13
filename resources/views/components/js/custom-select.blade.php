@props([
    'dark' => false,
])

@php
    $bgColor = $dark ? 'bg-dark' : 'bg-white';
    $textColor = $dark ? 'text-white' : 'text-black';
    $hoverBgColor = $dark ? 'hover:bg-black' : 'hover:bg-slate-200';
    $inputBgColor = $dark ? 'bg-dark text-white' : 'bg-slate-100 text-black';
    $borderColor = 'border-slate-300';
@endphp

@once
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
            selectedDiv.className = "selectSelected grid grid-cols-10 items-center {{ $bgColor }} text-slate-900 text-sm border border-solid {{ $borderColor }} rounded-xl my-1 px-4 py-2.5 relative cursor-pointer select-none";

            const selectedContent = document.createElement("div");
            selectedContent.className = "selectedContent col-span-9";
            selectedContent.innerHTML = selectElement.options[selectElement.selectedIndex].innerHTML;

            const arrow = document.createElement("div");
            arrow.className = "selectArrow col-span-1 absolute right-3 transform duration-200 ease-in-out";
            arrow.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>`;

            selectedDiv.append(selectedContent, arrow);
            customSelect.appendChild(selectedDiv);

            const itemsDiv = document.createElement("div");
            itemsDiv.className = "selectItems absolute w-full min-h-min max-h-52 top-full right-0 left-0 z-10 {{ $bgColor }} border border-solid border-t-none {{ $borderColor }} rounded-xl rounded-t-none overflow-y-auto -mt-1 hidden";

            const itemsDivSearch = document.createElement("span");
            itemsDivSearch.setAttribute("class", "block p-1");

            const searchInput = document.createElement("input");
            searchInput.setAttribute("type", "text");
            searchInput.setAttribute("placeholder", "Cari..");
            searchInput.setAttribute("class", "selectSearch block {{ $inputBgColor }} border border-solid {{ $borderColor }} focus:border-indigo-600 rounded-xl p-2 text-sm w-full outline-none");

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
                optionDiv.className = "cursor-pointer {{ $bgColor }} {{ $hoverBgColor }} text-sm p-2";

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
                    selectedDiv.classList.replace("text-slate-500", "{{ $textColor }}");

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
</script>
@endonce