const Utils = {
    methods: {
        addDate(oldDate, offset, offsetType) {
            var year = parseInt(oldDate.getFullYear())
            var month = parseInt(oldDate.getMonth())
            var date = parseInt(oldDate.getDate())
            var hour = parseInt(oldDate.getHours())
            var newDate
            switch (offsetType) {
            case "year":
                newDate = new Date(year + offset, month, date, hour)
                break;

            case "month":
                var yearOffset = 0;
                var monthOffset = 0;
                if (offset < 12) {
                yearOffset = Math.floor((month + offset) / 12)
                monthOffset = (month + offset) % 12
                } else {
                yearOffset = Math.floor(offset / 12)
                monthOffset = month % 12 + offset % 12
                }
                newDate = new Date(year + yearOffset, month + monthOffset, date, hour)
                break;


            case "day":
                var o = oldDate.getTime()
                var n = o + offset * 24 * 3600 * 1000
                newDate = new Date(n)
                break;

            case "hour":
                var o = oldDate.getTime()
                var n = o + offset * 3600 * 1000;
                newDate = new Date(n)
                break;

            case "minute":
                var o = oldDate.getTime()
                var n = o + offset * 60 * 1000;
                newDate = new Date(n)
                break;

            default:
                newDate = new Date(year + offset, month, date, hour);
            }
            return newDate;
        },
        getUTCDate(oldDate) {
            return this.addDate(oldDate, oldDate.getTimezoneOffset(), 'minute')
        },
        showTwoDecimals(num) {
            return +(Math.round(num + "e+2")  + "e-2")
        },
        notNullEmptyOrUndefined (object) {
          if (Array.isArray(object))
            return typeof(object) !== 'undefined' && object !== undefined && object !== null && object.length > 0
          else
            return typeof(object) !== 'undefined' && object !== undefined && object !== null && object !== '' && object !== 'null'
        }
    }
}