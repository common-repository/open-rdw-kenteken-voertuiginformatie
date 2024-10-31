/**
 * Method to be used for displaying alerts
 * 
 * @param {string} text 
 * @param {string} status [success, error, notice]
 * @param {string} remove [auto, manual]
 * @param {string} timeout 
 * @param {boolean} beforeHeaderEnd 
 */
export function Alert(text, status, remove = 'auto', timeout = 2000, beforeHeaderEnd = true)
{
    // Scroll to top so the alert will be in view
    window.scrollTo(0, 0);

    // Create the warning
    let $alert = document.createElement('div');
    
    // Add default classes to the warning
    $alert.classList.add('alert-' + status, 'alert', 'my-0', 'p-2', 'ps-3', 'shadow-none', 'position-relative', 'tussendoor-notice');
    
    // Create text node with given content
    let $text = document.createElement('p');
    $text.classList.add('m-0', 'p-0');
    $text.textContent = text;

    if (remove === 'manual') {
        // Create span node with given content
        let $removeButton = document.createElement('i');
        $removeButton.classList.add('js--remove-alert', 'custom--close-alert', 'fas', 'fa-solid', 'fa-xmark', 'p-2', 'position-absolute');
        
        // Append the button node to the warning
        $alert.appendChild($removeButton);

        // Add extra padding to the text when a close button is rendered
        $text.classList.add('pe-4');
    }
    
    // Append the text node to the warning
    $alert.appendChild($text);
    
    // Insert warning after wp-header-end
    let $headerEnd = document.getElementsByClassName('wp-header-end');
    let $element = beforeHeaderEnd ? $headerEnd.nextSibling : $headerEnd[0];

    $headerEnd[0].parentNode.insertBefore($alert, $element);

    if (remove === 'auto') {
        setTimeout(() => {
        
            $alert.style.transition = '0.2s';
            $alert.style.opacity = 0;
            
            setTimeout(() => {
                $alert.remove();
            }, 210);
        
        }, timeout);
    }
}