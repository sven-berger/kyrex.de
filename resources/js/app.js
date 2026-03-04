import "./counter";

import "./bootstrap";
import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Bold,
    Italic,
    Link,
    List,
    Heading,
    Code,
    CodeBlock,
    Table,
    TableToolbar,
    Undo,
} from "ckeditor5";
import "ckeditor5/ckeditor5.css";
import deTranslations from "ckeditor5/translations/de.js";
import hljs from "highlight.js/lib/core";
import javascript from "highlight.js/lib/languages/javascript";
import php from "highlight.js/lib/languages/php";
import xml from "highlight.js/lib/languages/xml";
import css from "highlight.js/lib/languages/css";
import bash from "highlight.js/lib/languages/bash";
import sql from "highlight.js/lib/languages/sql";
import json from "highlight.js/lib/languages/json";
import "highlight.js/styles/github.css";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

hljs.registerLanguage("javascript", javascript);
hljs.registerLanguage("php", php);
hljs.registerLanguage("xml", xml);
hljs.registerLanguage("css", css);
hljs.registerLanguage("bash", bash);
hljs.registerLanguage("sql", sql);
hljs.registerLanguage("json", json);

const editorTextareas = document.querySelectorAll(
    "textarea:not([data-no-editor])",
);

editorTextareas.forEach((textareaElement) => {
    if (textareaElement.dataset.editorInitialized === "1") {
        return;
    }

    textareaElement.dataset.editorInitialized = "1";

    ClassicEditor.create(textareaElement, {
        licenseKey: "GPL",
        language: "de",
        translations: [deTranslations],
        plugins: [
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Link,
            List,
            Heading,
            Code,
            CodeBlock,
            Table,
            TableToolbar,
            Undo,
        ],
        toolbar: [
            "undo",
            "redo",
            "|",
            "heading",
            "|",
            "bold",
            "italic",
            "link",
            "|",
            "bulletedList",
            "numberedList",
            "|",
            "code",
            "codeBlock",
            "insertTable",
        ],
        table: {
            contentToolbar: ["tableColumn", "tableRow", "mergeTableCells"],
        },
        codeBlock: {
            languages: [
                {
                    language: "plaintext",
                    label: "Plain text",
                    class: "language-plaintext",
                },
                {
                    language: "javascript",
                    label: "JavaScript",
                    class: "language-javascript",
                },
                { language: "php", label: "PHP", class: "language-php" },
                { language: "html", label: "HTML", class: "language-html" },
                { language: "css", label: "CSS", class: "language-css" },
                { language: "bash", label: "Bash", class: "language-bash" },
                { language: "sql", label: "SQL", class: "language-sql" },
                { language: "json", label: "JSON", class: "language-json" },
            ],
        },
    })
        .then((editor) => {
            editor.model.document.on("change:data", () => {
                textareaElement.value = editor.getData();
            });
        })
        .catch((error) => {
            console.error(error);
        });
});

document
    .querySelectorAll(".wp-rich-content pre code")
    .forEach((codeElement) => {
        hljs.highlightElement(codeElement);
    });
