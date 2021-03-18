export const g = {
    getDate(date){
        let new_date = new Date(date);
        var month = new_date.getMonth()+1 <= 9 ? '0'+(new_date.getMonth()+1) : new_date.getMonth()+1;
        var day = new_date.getDate() <= 9 ? '0'+new_date.getDate() : new_date.getDate();
        var year = new_date.getFullYear();
        return day + "." + month + "." + year;
    },
}
