function bindVisibilityEvents(hideableContents) {
    hideableContents.forEach(function (elem, index, arr) {
        //Now bind the toggle visibility functions
        var buttonName = elem.selector + "VisibilityToggle";
        var $button = $(buttonName);

        if (index > 0) {
            elem.hide(); //Hide all elems by default
            $button.addClass("toggleButtonOff")
        } else {
            $button.addClass("toggleButtonOn")
        }
        $button.addClass("toggleButton")

        $button.click(function () {
            if ($button.hasClass("toggleButtonOff")) {
                toggleContentVisibility(elem, hideableContents);
                $button.removeClass("toggleButtonOff")
                $button.addClass("toggleButtonOn")
            }
        });
    });
}

function toggleContentVisibility(contentDiv, hideableContents) {
    contentDiv.slideToggle(200, function () {
        hideableContents.forEach(
            function (elem, index, arr) {
                if (elem.selector != contentDiv.selector) {
                    elem.hide();
                    var buttonName = elem.selector + "VisibilityToggle";
                    var $button = $(buttonName);
                    if ($button.hasClass("toggleButtonOn")) {
                        $button.removeClass("toggleButtonOn")
                        $button.addClass("toggleButtonOff")
                    }
                }
            }
        );
    });
}

//Courtsey: http://stackoverflow.com/a/901144
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

//Courtsey: http://stackoverflow.com/a/133997
function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function httpGetAsync(theUrl, callback, errorCallback = null) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            if (xmlHttp.status == 200) {
                callback(xmlHttp.responseText);
            } else if (errorCallback) {
                errorCallback(xmlHttp.response);
            }
        }
    }
    xmlHttp.open("GET", theUrl, true); // true for asynchronous 
    xmlHttp.send(null);
}

function convertTopicToFriendlyName(topicInternalName) {
    return topicInternalName.split(".").join(" ");
}

function convertTopicToInternalName(topicInternalName) {
    return topicInternalName.split(" ").join(".");
}

function createLinkWithHref(content, href) {
    var linkElem = document.createElement("a");
    linkElem.appendChild(document.createTextNode(content));
    linkElem.href = href;
    return linkElem;
}

function createLinkWithOnClick(content, onclickCallback) {
    var linkElem = document.createElement("a");
    linkElem.appendChild(document.createTextNode(content));
    linkElem.onclick = onclickCallback;
    return linkElem;
}

function appendAsOnlyChild(selector, childElem) {
    $(selector).empty();
    $(selector).append(childElem);
}
