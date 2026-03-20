const fs = require('fs');

const filesToInclude = ['index.php', 'cart.php', 'checkout.php', 'style.css'];
let docContent = `<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'Courier New', Courier, monospace; font-size: 10.5pt; }
    p { margin: 0; padding: 0; }
    h2 { font-family: Arial, sans-serif; margin-top: 20px; margin-bottom: 10px; }
</style>
</head>
<body>
`;

filesToInclude.forEach(file => {
    if (fs.existsSync(file)) {
        docContent += `<h2>FILE: ${file}</h2>\n`;
        const content = fs.readFileSync(file, 'utf8');
        const lines = content.split('\n');
        
        lines.forEach(line => {
            // Remove ALL leading spaces and tabs to ensure it starts from left margin
            let trimmedLine = line.trimStart();
            
            // Escape HTML entities to display code correctly
            trimmedLine = trimmedLine.replace(/&/g, '&amp;')
                                     .replace(/</g, '&lt;')
                                     .replace(/>/g, '&gt;');
                                     
            // Skip completely empty lines to make it more compact (optional, keeping for now but as non-breaking space if empty)
            if (trimmedLine.length === 0) {
                docContent += `<p>&nbsp;</p>\n`;
            } else {
                docContent += `<p>${trimmedLine}</p>\n`;
            }
        });
        docContent += `<br/>\n`;
    }
});

docContent += `</body>\n</html>`;

fs.writeFileSync('Shopping_Cart_Code_Unindented.doc', docContent, 'utf8');
console.log('Successfully generated Shopping_Cart_Code_Unindented.doc');
