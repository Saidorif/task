export const g = {
    getDate(date){
        let new_date = new Date(date);
        var month = new_date.getMonth()+1 <= 9 ? '0'+(new_date.getMonth()+1) : new_date.getMonth()+1;
        var day = new_date.getDate() <= 9 ? '0'+new_date.getDate() : new_date.getDate();
        var year = new_date.getFullYear();
        return day + "." + month + "." + year;
    },
    toWord(id,name){
        let header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
        "xmlns:w='urn:schemas-microsoft-com:office:word' "+
        "xmlns='http://www.w3.org/TR/REC-html40'>"+
        `<head>
            <meta charset='utf-8'>
            <title>Export HTML to Word Document with JavaScript</title>
            <style>
                @page landscape_A4_page {
                    size:841.9pt 595.3pt;
                    mso-page-orientation:landscape;
                    margin:39.0pt 51.0pt 39.0pt 51.0pt;
                    mso-header-margin:35.45pt;
                    mso-footer-margin:35.45pt;
                    mso-paper-source:0;
                }
                .landscape_A4_page { page: landscape_A4_page; }
                .landscape_A4_page h6{
                    font-size: 12pt;
                    text-align:center;
                    color: rgb(0, 32, 96);
                    font-family: Arial,sans-serif;
                }
                .landscape_A4_page table{
                    font-family: Arial,sans-serif;
                    border-spacing: 0px;
                    border-collapse: separate;
                    border: 1px solid #000;
                    border-bottom:none;
                }
                .landscape_A4_page table thead{
                    border: 1px solid #000;
                }
                .landscape_A4_page table tr{
                    border: 1px solid #000;
                    border-collapse: separate;
                    border-spacing: 0px;
                }
                .landscape_A4_page table td,
                .landscape_A4_page table th{
                    font-size: 12pt;
                    border-right: 1px solid #000;
                    border-bottom: 1px solid #000;
                    border-spacing: 0px;
                    border-collapse: separate;
                }
                .landscape_A4_page table th{
                    text-align:center;
                }
                .landscape_A4_page table tr p{
                    margin: 0px;
                }
                .landscape_A4_page table tr .selected{
                    color: rgb(192, 0, 0);
                    font-weight: bold;
                }
            </style>
        </head>
        <body>`;
         let footer = "</body></html>";
         let sourceHTML = header+document.getElementById(id).innerHTML+footer;

         let source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
         let fileDownload = document.createElement("a");
          document.body.appendChild(fileDownload);
          fileDownload.href = source;
          fileDownload.download = name+'.doc';
          fileDownload.click();
          document.body.removeChild(fileDownload);
    },
}
