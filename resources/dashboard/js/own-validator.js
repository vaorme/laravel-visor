class OwnValidator {
    constructor(element) {
        this.element = element;
    }
    validate() {
        const elements = this.element.elements;
        const errors = [];
        if(elements.length <= 0){
            console.log('NO hay elementor para iterar');
            return false;
        }
        for(const element of elements){
            if(element.hasAttribute('required') && element.value == ""){
                element.classList.add('is-invalid');
                if(element.classList.contains('tomselected')){
                    element.nextElementSibling.classList.add('is-invalid');
                }
                errors.push(`${element.getAttribute('name')} is required.`);
            }else{
                element.classList.remove('is-invalid');
                if(element.classList.contains('tomselected')){
                    element.nextElementSibling.classList.remove('is-invalid');
                }
            }
        }
        if (errors.length > 0) {
            return false;
        }else{
            return true;
        }
    };
    validateOnChange(){
        const elements = this.element.elements;
        if(elements.length <= 0){
            console.log('NO hay elementor para iterar');
            return false;
        }
        for(const element of elements){
            element.addEventListener('input', function(){
                if(element.hasAttribute('required') && element.value == ""){
                    element.classList.add('is-invalid');
                    if(element.classList.contains('tomselected')){
                        element.nextElementSibling.classList.add('is-invalid');
                    }
                }else{
                    element.classList.remove('is-invalid');
                    if(element.classList.contains('tomselected')){
                        element.nextElementSibling.classList.remove('is-invalid');
                    }
                }
            })
        }
    }
    comicValidate() {
        const elements = this.element.elements;
        const errors = [];
        if(elements.length <= 0){
            console.log('NO hay elementor para iterar');
            return false;
        }
        for(const element of elements){
            if(element.hasAttribute('required') && element.value == ""){
                element.classList.add('is-invalid');
                if(element.classList.contains('tomselected')){
                    element.nextElementSibling.classList.add('is-invalid');
                }
                const parent = findParentByClass(element, 'accordion-item');
                if(parent){
                    if(parent.children[0].children[0].classList.contains('collapsed')){
                        parent.children[0].children[0].click()
                    }
                }
                errors.push(`${element.getAttribute('name')} is required.`);
            }else{
                element.classList.remove('is-invalid');
                if(element.classList.contains('tomselected')){
                    element.nextElementSibling.classList.remove('is-invalid');
                }
            }
        }
        if (errors.length > 0) {
            return false;
        }else{
            return true;
        }
    };
    comicValidateOnChange(){
        const elements = this.element.elements;
        if(elements.length <= 0){
            console.log('NO hay elementor para iterar');
            return false;
        }
        for(const element of elements){
            element.addEventListener('input', function(){
                if(element.hasAttribute('required') && element.value == ""){
                    element.classList.add('is-invalid');
                    if(element.classList.contains('tomselected')){
                        element.nextElementSibling.classList.add('is-invalid');
                    }
                    const parent = findParentByClass(element, 'accordion-item');
                    if(parent){
                        if(parent.children[0].children[0].classList.contains('collapsed')){
                            parent.children[0].children[0].click()
                        }
                    }
                }else{
                    element.classList.remove('is-invalid');
                    if(element.classList.contains('tomselected')){
                        element.nextElementSibling.classList.remove('is-invalid');
                    }
                }
            })
        }
    }
};

function findParentByClass(element, className) {
    // Start with the current element
    let currentElement = element;
  
    // Continue looping until we reach the document body or a null parent
    while (currentElement && !currentElement.classList.contains(className)) {
      // Move up to the parent element
      currentElement = currentElement.parentElement;
    }
  
    // Return the found parent element or null if not found
    return currentElement;
}

function openItemAccordion(index) {
    const accordion = document.querySelector('#sidebar-accordion');
    accordion.children[index].children[0].children[0].click()
}

export default OwnValidator