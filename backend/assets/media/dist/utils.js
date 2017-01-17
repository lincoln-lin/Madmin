// JS工具类

var Utils = {

    //----------------------------------------
    // 字符串类型转换
    //----------------------------------------

    StrToStr: function (str, defaultValue) {
        ///	<summary>
        ///	字符串为空时返回默认值
        ///	</summary>
        return this.IsNullOrEmpty(str) || str == "null" ? defaultValue : str;
    },
	
	CreatePassword:function(limit){		// 生成复杂密码    limit 密码长度
		limit 		= parseInt(limit, 10);
		create_arr	= ['A','B','C','D','E','F','G','H','J','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z'];
		create_arr2	= ['a','b','c','d','e','f','g','h','i','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z'];
		create_arr3	= ['2','3','4','5','6','7','8','9'];
		create_arr4 = ['!','@','#','$'];
		
		var password 	= "";
		for(i=0;i<limit;i++){
			var seed = Math.ceil( Math.random()*20 );
			if(seed<7){
				leng 	= create_arr.length;
				rad 	= Math.floor(Math.random()*leng);
				password += create_arr[rad];
			}else if(seed<12){
				leng 	= create_arr2.length;
				rad 	= Math.floor(Math.random()*leng);
				password += create_arr2[rad];
			}else if(seed<16){
				leng 	= create_arr3.length;
				rad 	= Math.floor(Math.random()*leng);
				password += create_arr3[rad];
			}else{
				leng 	= create_arr4.length;
				rad 	= Math.floor(Math.random()*leng);
				password += create_arr4[rad];
			}
		}
		return password;
	},
    CreatePasswordAlphanumeric:function(limit){		// 生成复杂密码    limit 密码长度
        limit 		= parseInt(limit, 10);
        create_arr	= ['A','B','C','D','E','F','G','H','J','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z'];
        create_arr2	= ['a','b','c','d','e','f','g','h','i','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z'];
        create_arr3	= ['2','3','4','5','6','7','8','9'];

        var password 	= "";
        for(i=0;i<limit;i++){
            var seed = Math.ceil( Math.random()*20 );
            if(seed < 8){
                leng 	= create_arr.length;
                rad 	= Math.floor(Math.random()*leng);
                password += create_arr[rad];
            }else if(seed < 15){
                leng 	= create_arr2.length;
                rad 	= Math.floor(Math.random()*leng);
                password += create_arr2[rad];
            } else {
                leng 	= create_arr3.length;
                rad 	= Math.floor(Math.random()*leng);
                password += create_arr3[rad];
            }
        }
        return password;
    },
    StrToDateTime: function (strdate) {
        ///	<summary>
        ///	将字符串转换为日期类型（参考格式 2008-12-09T13:06:27Z）
        ///	</summary>
        var strYear = strdate.substring(0, 4);
        var strMonth = strdate.substring(5, 7);
        var strDay = strdate.substring(8, 10);
        var strHours = strdate.substring(11, 13);
        var strMinutes = strdate.substring(14, 16);
        var strSecond = strdate.substring(17, 19);
        return new Date(strYear, strMonth - 1, strDay, strHours, strMinutes, strSecond);
    },
    getLocalTime: function (nS){
    return new Date(parseInt(nS) * 1000).toLocaleString().substr(0,16)
    },
    GetQueryStr: function (name) {
        ///	<summary>
        ///	获得Url查询字符串(带格式化)
        ///	</summary>
        return this.StrToStr(decodeURI(this.GetQueryString(name)), "");
    },

    InsetLen: function (str, str1, char, len) {
        ///	<summary>
        ///	在字符串中间插入空格使字符串到提定长度
        ///	</summary>
        var slen = this.GetStrLen(str.replace(" ", "")) + this.GetStrLen(str1.replace(" ", ""));
        var alen = len - slen;
        if (alen <= 0) { return str + str1; }
        for (var i = 0; i < alen; i++) { str += char; }
        return str + str1;
    },

    //----------------------------------------
    // 字符串长度计算与截取
    //----------------------------------------

    GetStrLen: function (str) {
        ///	<summary>
        ///	返回字符串的长度
        ///	</summary>
        ///<param name="str" type="String">字符串</param>
        //str = this.Trim(str); 
        return str.replace(/[^\x00-\xff]/g, "**").length;
    },

    ValStrLen: function (str, max, min) {
        ///	<summary>
        ///	判断字符串是否符合长度要求
        ///	</summary>
        ///<param name="str" type="String">字符串</param>
        ///<param name="max" type="Number">最大长度</param>
        ///<param name="min" type="Number">最小长度</param>
        if (arguments.length == 2)
            return this.GetStrLen(str) <= max ? true : false;
        else if (arguments.length == 3) {
            var strLen = this.GetStrLen(str); return ((min <= strLen) && (strLen <= max)) ? true : false;
        }
    },

    //----------------------------------------
    // 字符串替换与截取
    //----------------------------------------

    Trim: function (str) {
        ///	<summary>
        ///	去掉字符串起始和结尾的空格
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        if (str == null || str == "") return null;
        return str.replace(/(^\s*)|(\s*$)/g, "");
    },

    LTrim: function (str) {
        ///	<summary>
        ///	去掉字符串起始的空格
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return str.replace(/(^\s*)/g, "");
    },

    RTrim: function (str) {
        ///	<summary>
        ///	去掉字符串结尾的空格
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        
        return str.replace(/(\s*$)/g, "");
    },

    GetExname: function (fileName) {
        ///	<summary>
        ///	返回文件后缀名, 如：PNG JPG
        ///	</summary>
        ///<param name="str" type="String">字符串</param>
        return fileName.substring(fileName.lastIndexOf(".") + 1);
    },

    GetUrlPathName: function (fullUrl, host) {
        ///	<summary>
        ///	从完整Url中获得某部分
        ///	</summary>
        //      'URL'         : 0
        //		'Protocol'    : 2
        //		'Username'    : 4
        //		'Password'    : 5
        //		'Host'        : 6
        //		'Port'        : 7
        //		'Pathname'    : 8
        //		'Querystring' : 9
        //		'Fragment'    : 10

        fullUrl = this.IsNullOrEmpty(fullUrl) ? location.href : fullUrl;
        host = this.IsNullOrEmpty(host) ? 8 : host;

        var regex = /^((\w+):\/\/)?((\w+):?(\w+)?@)?([^\/\?:]+):?(\d+)?(\/?[^\?#]+)?\??([^#]+)?#?(\w*)/;
        var r = regex.exec(fullUrl);
        if (!r) throw "DPURLParser::_parse -> Invalid URL";
        return r[host];
    },

    GetEmailHostName: function (strEmail) {
        ///	<summary>
        ///	获得Email的主机名
        ///	</summary>
        ///<param name="str" type="String">Email</param>
        if (strEmail.indexOf("@") < 0) {
            return "";
        }
        return strEmail.substring(strEmail.lastIndexOf("@"));
    },

    ///	<summary>
    ///	获得文件名
    ///	</summary>
    ///<param name="str" type="String">Email</param>
    GetFullFileName: function (fileAddress) {
        if (this.IsNullOrEmpty(fileAddress)) return null;
        var str;
        if (fileAddress.lastIndexOf("\\") > fileAddress.lastIndexOf("/")) {
            str = "\\";
        }
        else {
            str = "/";
        }
        return fileAddress.substring(fileAddress.lastIndexOf(str) + 1);
    },

    //----------------------------------------
    // 字符串格式判断
    //----------------------------------------

    IsNullOrEmpty: function (str) {
        ///	<summary>
        ///	是否为空，为Null,""," "和多个空格时返回true
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        if (str == null || str == "undefined") return true;
        return /^\s*$/.test(str);

    },

    IsNumber: function (str) {
        ///	<summary>
        ///	是否为数字类型
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        if (this.IsNullOrEmpty(str)) return false;
        return !isNaN(str);

    },

    IsInt: function (str) {
        ///	<summary>
        ///	是否为整数
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^-?[0-9]\d*$/.test(str);
    },

    IsPInt: function (str) {
        ///	<summary>
        ///	是否为正整数
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^[0-9]\d*$/.test(str);
    },

    IsNInt: function (str) {
        ///	<summary>
        ///	是否为负整数
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^-[1-9]\d*$/.test(str);
    },

    IsDate: function (adDate) {
        // 从1000到9999年的日期格式
        var pattern = /^[1-9]\d{3}-((0[1-9]{1})|(1[0-2]{1}))-((0[1-9]{1})|([1-2]{1}\d{1})|(3[0-1]{1}))$/;
        if (!pattern.test(adDate)) {
            return false;
        }

        var arrAdDate = adDate.split("-");
        var adYear = parseInt(arrAdDate[0]);
        var month = parseInt(arrAdDate[1]);
        var day = parseInt(arrAdDate[2]);
        dateTmp = new Date(adYear, month - 1, day);
        if (dateTmp.getFullYear() != adYear || dateTmp.getMonth() != month - 1 || dateTmp.getDate() != day) {
            return false;
        }
        return true;
    },

    IsFullDate: function (str) {
        ///	<summary>
        ///	是否为 yyyy-mm-dd 日期格式
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^\d{4}-\d{2}-\d{2}$/.test(str);

    },

    IsTime: function (str) {
        ///	<summary>
        ///	是否为 0:0:0 或 00:00:00  时间格式
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^((([0-1]?[0-9])|(2[0-3])):([0-5]?[0-9])(:[0-5]?[0-9])?)$/.test(str);

    },

    IsFullTime: function (str) {
        ///	<summary>
        ///	是否为（00:00:00）时间格式
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^((([0-1][0-9])|(2[0-3])):([0-5][0-9])(:[0-5][0-9])?)$/.test(str);

    },

    IsColor: function (str) {
        ///	<summary>
        ///	是否为有效的3/6颜色格式
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(str);

    },

    IsEmail: function (str) {
        ///	<summary>
        ///	是否为有效的有效的Email格式
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^[\w\.-]+@[\w-]+([\.][\w-]+)+$/.test(str);

    },

    IsImg: function (str) {
        ///	<summary>
        ///	是否为图片文件（jpg/jpeg/png/bmp/gif）
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /(\.jpg|\.jpeg|\.png|\.gif)$/.test(str);

    },

    IsNumList: function (str) {
        ///	<summary>
        ///	是否为由","间隔的数值串列表 2,3,4,4,5,6,7,88,
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^([\d]+,)*[\d]+,?$/.test(str);

    },

    IsAttSQL: function (str) {
        ///	<summary>
        ///	是否包含危险SQL： - ; , / (  ) [ ] { } % @ * ! '
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /-|;|,|\/|\(|\)|\[|\]|\}|\{|%|@|\*|!|'/.test(str);

    },

    IsAttString: function (str) {
        ///	<summary>
        ///	是否危险字符串(可能用于链接的字符串)：空格 制表符 \ c:\con\con % * @ & " 游客 Guest
        ///	</summary>
        ///	<param name="str" type="String">一个字串符</param>
        return /^\s*$|^c:\\con\\con$|[%,\*\\@\s\t\<\>\&"]|游客|^Guest/.test(str);

    },

    //----------------------------------------
    // 字符串数组
    //----------------------------------------

    IsInArray: function (str, array, split) {
        ///	<summary>
        ///	判断字符串是否在数组中
        ///	</summary>
        ///<param name="str" type="String">字符串</param>
        ///<param name="array" type="Array">数组</param>
        ///<param name="split" type="String">分隔符</param>
        if (arguments.length > 2) {
            array = array.split(split);
        }

        for (var i = 0; i < array.length; i++) {
            if (str == this.Trim(array[i])) return true;
        }
        return false;
    },

    //----------------------------------------
    // 字符串格式化
    //----------------------------------------

    FormatDate: function (date, fmt) {
        ///	<summary>
        ///	按指定的格式输出日期字符串
        ///	</summary>
        ///<param name="date" type="Date">日期时间</param>
        ///<param name="str" type="String">表达式</param>

        // 例：Utils.FormatDate(workResult.ServerDateTime, "yyyy-MM-dd hh:mm:ss")

        var o = {
            "M+": date.getMonth() + 1,                      //月份
            "d+": date.getDate(),                           //日
            "h+": date.getHours(),                          //小时
            "m+": date.getMinutes(),                        //分
            "s+": date.getSeconds(),                        //秒
            "q+": Math.floor((date.getMonth() + 3) / 3),    //季度
            "S": date.getMilliseconds()                     //毫秒 
        };
        if (/(y+)/.test(fmt))
            fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
            if (new RegExp("(" + k + ")").test(fmt))
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    },

    NumberFormat: function (num, pattern) {
        ///	<summary>
        ///	按指定的格式输出数字字符串
        ///	</summary>

        // 例：NumberFormat(order.GoodsPrice, '#,##0.00')

        var strarr = num ? num.toString().split('.') : ['0'];
        var fmtarr = pattern ? pattern.split('.') : [''];
        var retstr = '';

        // 整数部分   
        var str = strarr[0];
        var fmt = fmtarr[0];
        var i = str.length - 1;
        var comma = false;
        for (var f = fmt.length - 1; f >= 0; f--) {
            switch (fmt.substr(f, 1)) {
                case '#':
                    if (i >= 0) retstr = str.substr(i--, 1) + retstr;
                    break;
                case '0':
                    if (i >= 0) retstr = str.substr(i--, 1) + retstr;
                    else retstr = '0' + retstr;
                    break;
                case ',':
                    comma = true;
                    retstr = ',' + retstr;
                    break;
            }
        }
        if (i >= 0) {
            if (comma) {
                var l = str.length;
                for (; i >= 0; i--) {
                    retstr = str.substr(i, 1) + retstr;
                    if (i > 0 && ((l - i) % 3) == 0) retstr = ',' + retstr;
                }
            }
            else retstr = str.substr(0, i + 1) + retstr;
        }

        retstr = retstr + '.';
        // 处理小数部分   
        str = strarr.length > 1 ? strarr[1] : '';
        fmt = fmtarr.length > 1 ? fmtarr[1] : '';
        i = 0;
        for (var f = 0; f < fmt.length; f++) {
            switch (fmt.substr(f, 1)) {
                case '#':
                    if (i < str.length) retstr += str.substr(i++, 1);
                    break;
                case '0':
                    if (i < str.length) retstr += str.substr(i++, 1);
                    else retstr += '0';
                    break;
            }
        }
        return retstr.replace(/^,+/, '').replace(/\.$/, '');
    },

    FormatSize: function (size) {
        ///	<summary>
        ///	按指定的格式输出容量大小字符串
        ///	</summary>
        if (size <= 0) return "0 Byte";
        if (size > 0 && size < 1024) return size + " Byte";
        if (size >= 1024 && size < Math.pow(1024, 2)) return Math.round((size / 1024) * 100) / 100 + " KB";
        if (size >= 1024 * 1024 && size < Math.pow(1024, 3)) return Math.round((size / Math.pow(1024, 2)) * 100) / 100 + " MB";
        if (size >= Math.pow(1024, 3)) return Math.round((size / Math.pow(1024, 3)) * 100) / 100 + " G";
        return "";
    },
    FormatSeconds: function (seconds) {
        ///	<summary>
        ///	将时间戳转化为日+小时+分+秒
        //   转化为 日+小时+分+秒
        ///	</summary>
        var time = parseInt(seconds);
        if (time != null && time != ""){
            if (time < 60) {
                var s = time;
                time = s + '秒';
            } else if (time >= 60 && time < 3600) {
                var m = parseInt(time / 60);
                var s = parseInt(time % 60);
                time = m + "分钟";
                if(s > 0) {
                    time = time + s + "秒"
                }
            } else if (time >= 3600 && time < 86400) {
                var h = parseInt(time / 3600);
                var m = parseInt(time % 3600 / 60);
                var s = parseInt(time % 3600 % 60 % 60);
                time = h + "小时";
                if(m > 0) {
                    time = time + m + "分"
                }
                if(s > 0) {
                    time = time + s + "秒"
                }
            } else if (time >= 86400) {
                var d = parseInt(time / 86400);
                var h = parseInt(time % 86400 / 3600);
                var m = parseInt(time % 86400 % 3600 / 60)
                var s = parseInt(time % 86400 % 3600 % 60 % 60);
                time = d + '天' + h + "小时" + m + "分" + s + "秒";
                if(h > 0) {
                    time = time + h + "小时"
                }
                if(m > 0) {
                    time = time + m + "分"
                }
                if(s > 0) {
                    time = time + s + "秒"
                }
            }
        }
        return time;
    },
    //----------------------------------------
    // 日期时间
    //----------------------------------------

    IsLeapYear: function (y) {
        ///	<summary>
        ///	判断是否为闰年
        ///	</summary>
        ///	<param name="y" type="String">年份</param>
        return y % 4 == 0 && (y % 400 == 0 || y % 100 != 0);
    },

    IsExistDate: function (y, m, d) {
        ///	<summary>
        ///	判断日期是否正确
        ///	</summary>
        ///	<param name="y" type="String">年</param>
        ///	<param name="m" type="String">月</param>
        ///	<param name="d" type="String">日</param>
        if (y < 1 || d < 1 || m < 1 || m > 12) return false;
        if (m == 2)
            if (this.IsLeapYear(y)) return d <= 29; else return d <= 28;
        else if (m == 4 || m == 6 || m == 9 || m == 11)
            return d <= 30;
        else
            return d <= 31;
    },

    ///	<summary>
    ///	将日期转换为相对日期文本
    ///	</summary>
    DateFmtDiff: function (today, date) {
        if (date > today) return this.DateFmtDiff1(date, today);
        if (this.DateDiff(date, today, "s") < 1) { return "刚刚"; }
        if (this.DateDiff(date, today, "s") <= 60) { return this.DateDiff(date, today, "s") + "秒前"; }
        if (this.DateDiff(date, today, "n") <= 60) { return this.DateDiff(date, today, "n") + "分前"; }
        if (this.DateDiff(date, today, "h") <= 24) { return this.DateDiff(date, today, "h") + "小时前"; }
        if (this.DateDiff(date, today, "d") <= 7) { return this.DateDiff(date, today, "d") + "天前"; }
        if (this.DateDiff(date, today, "w") <= 4) { return this.DateDiff(date, today, "w") + "周前"; }
        if (this.DateDiff(date, today, "m") <= 12) { return this.DateDiff(date, today, "m") + "个月前"; }
        return this.DateDiff(date, today, "y") + "年前";
    },

    ///	<summary>
    ///	将日期转换为相对日期文本1
    ///	</summary>
    DateFmtDiff1: function (today, date) {
        if (date > today) return this.DateFmtDiff(date, today);
        if (this.DateDiff(date, today, "s") < 1) { return "刚刚"; }
        if (this.DateDiff(date, today, "s") <= 60) { return this.DateDiff(date, today, "s") + "秒后"; }
        if (this.DateDiff(date, today, "n") <= 60) { return this.DateDiff(date, today, "n") + "分后"; }
        if (this.DateDiff(date, today, "h") <= 24) { return this.DateDiff(date, today, "h") + "小时后"; }
        if (this.DateDiff(date, today, "d") <= 7) { return this.DateDiff(date, today, "d") + "天后"; }
        if (this.DateDiff(date, today, "w") <= 4) { return this.DateDiff(date, today, "w") + "周后"; }
        if (this.DateDiff(date, today, "m") <= 12) { return this.DateDiff(date, today, "m") + "个月后"; }
        return this.DateDiff(date, today, "y") + "年前";
    },

    ///	<summary>
    ///	将日期转换为相对日期文本工具方法
    ///	</summary>
    DateDiff: function (dtStart, dtEnd, strInterval) {

        switch (strInterval) {
            case 's': return parseInt((dtEnd - dtStart) / 1000);
            case 'n': return parseInt((dtEnd - dtStart) / 60000);
            case 'h': return parseInt((dtEnd - dtStart) / 3600000);
            case 'd': return parseInt((dtEnd - dtStart) / 86400000);
            case 'w': return parseInt((dtEnd - dtStart) / (86400000 * 7));
            case 'm': return (dtEnd.getMonth() + 1) + ((dtEnd.getFullYear() - dtStart.getFullYear()) * 12) - (dtStart.getMonth() + 1);
            case 'y': return dtEnd.getFullYear() - dtStart.getFullYear();
        }
    },

    //----------------------------------------
    // 网络相关
    //----------------------------------------

    // 获得指定的查询字符串的值
    GetQueryString: function (name) {
        ///	<summary>
        ///	获得当前页URL中的查询字符串
        ///	</summary>
        if (window.location.search == "") return null;
        name = name.toLowerCase();
        var str = window.location.search.substring(1, window.location.search.length);
        var arry = str.split("&");
        for (var i = 0; i < arry.length; i++) { if (arry[i].split("=")[0].toLowerCase() == name) return arry[i].split("=")[1]; }
        return null;
    },

    GetQueryLength: function () {
        ///	<summary>
        ///	获得当前页URL中的查询字符串的长度
        ///	</summary>
        if (window.location.search == "") return 0;
        return window.location.search.substring(1, window.location.search.length).split("&").length;
    },

    HtmlDecode: function (text) {
        if (text == null || text == "") return null; return text.replace(/&amp;/g, '&').replace(/&quot;/g, '\"').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
    },

    HtmlEncode: function (text) {
        if (text == null || text == "") return null; return text.replace(/&/g, '&amp').replace(/\"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    },

    //----------------------------------------
    // html相关
    //----------------------------------------

    // 过滤html
    FilterHtml: function (str) {
        str = str.replace(/<\/?[^>]*>/g, ''); //去除HTML tag
        str.value = str.replace(/[ | ]*\n/g, '\n'); //去除行尾空白
        //str = str.replace(/\n[\s| | ]*\r/g,'\n'); //去除多余空行
        return str;
    },

    //----------------------------------------
    // Ajax相关
    //----------------------------------------

    AjaxResult: function (fileName, parameters) {
        ///	<summary>
        ///	调用远程页面方法
        ///	</summary>
        if (parameters == undefined) parameters = null;

        var result = null;

        $.ajax({
            async: false,
            type: "POST",
            data: parameters,
            url: fileName,
            dataType: "json",
            success: function (transport) {

                result = transport;

                // 调试时用的代码
                if (result == null) { alert("调用远程方法返回的结果为空！"); }
                if (result.status == null) { alert("调用远程方法返回的结果status为空！"); }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {

                // 调试时用的代码
                $("body").append(XMLHttpRequest.responseText).css("padding", 10); alert("调用远程方法时出错！");
            }
        });

        return result;
    },

    //----------------------------------------
    // 窗口相关
    //----------------------------------------

    IsiPad: function () {
        return /(iPhone|iPad|iPod)/i.test(navigator.userAgent);
    },

    OpenCenterWin: function (url, isNew) {
    	Utils.CenterPopup(url, url, 0.8, 0.7, "yes", "yes");
    },

    OpenWindow: function (url, width, height, name, toolbar, menubar, scrollbars, resizable, location, status) {

        var size;
        if (!G.IsNInt(width) || this.IsNullOrEmpty(width) || this.IsNullOrEmpty(height)) {
            switch (width) {
                case "big":
                    size = { width: 160, height: 300 }; break;
                case "none":
                    size = { width: 400, height: 400 }; break;
                case "min":
                    size = { width: 600, height: 500 }; break;
                default:
                    size = { width: 400, height: 400 }; break;
            }

            width = window.screen.availWidth - size.width;
            height = window.screen.availHeight - size.height;
        }

        name = this.IsNullOrEmpty(name) ? "" : name;
        toolbar = this.IsNullOrEmpty(toolbar) ? "no" : toolbar;
        menubar = this.IsNullOrEmpty(menubar) ? "yes" : menubar;
        scrollbars = this.IsNullOrEmpty(scrollbars) ? "yes" : scrollbars;
        resizable = this.IsNullOrEmpty(resizable) ? "yes" : resizable;
        location = this.IsNullOrEmpty(location) ? "yes" : location;
        status = this.IsNullOrEmpty(status) ? "yes" : status;

        var left = (window.screen.availWidth - width) / 2;
        var top = (window.screen.availHeight - height) / 2;
        return window.open(url, name, "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top + ",screenX=" + left + ",screenY=" + top + ",toolbar=" + toolbar + ",menubar=" + menubar + ",scrollbars=" + scrollbars + ",resizable=" + resizable + ",location=" + location + ",status=" + status);
    },

    CenterPopup: function (url, target, w, h, scroll, resiz) {

        if (w < 1 && h < 1) {
            // 自动计算宽度和高度
            w = screen.width * w;
            h = screen.height * h;
        }

        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        var winprops = 'height=' + h + ',width=' + w + ',top=' + wint + ',left=' + winl + ',scrollbars=' + scroll + ',resizable=' + resiz + ', toolbars=false, status=false, menubar=false';
        var win = window.open(url, Math.random(), (this.IsiPad() ? null : winprops));
        if (!win)
            alert("A popup blocker was detected: please allow them for this application (check out the upper part of the browser window).");
        if (parseInt(navigator.appVersion) >= 4) {
            win.window.focus();
        }
    },

    CloseWin: function () {
        var ua = navigator.userAgent
        var ie = navigator.appName == "Microsoft Internet Explorer" ? true : false
        if (ie) {
            var IEversion = parseFloat(ua.substring(ua.indexOf("MSIE ") + 5, ua.indexOf(";", ua.indexOf("MSIE "))))
            if (IEversion < 5.5) {
                var str = '<object id=noTipClose classid="clsid:ADB880A6-D8FF-11CF-9377-00AA003B7A11">'
                str += '<param name="Command" value="Close"></object>';
                document.body.insertAdjacentHTML("beforeEnd", str);
                document.all.noTipClose.Click();
            }
            else {
                window.opener = null;
                window.close();
            }
        }
        else {
            window.close()
        }
    },

    IsMaxWinWidth: function () {
        return window.screen.width == document.body.scrollWidth;
    },
    PreviewHtmlContent: function (content) {
        var win = window.open('', '_blank', '');
        win.document.open("text/html", "replace");
        win.opener = null;
        win.document.write(content);
        win.document.close();
    },
    GotoUrl: function (url) {
        ///	<summary>
        ///	跳转到指定页面，在指定页面可以通过document.referrer得到上页url，解决ie&ff
        ///	</summary>
        ///<param name="url" type="String">要跳转的页面</param>
        url = this.IsNullOrEmpty(url) ? location.href : url;
        var f = document.createElement('a');
        f.href = url;
        document.body.appendChild(f);
        if (document.all && typeof (document.all) == "object")
            f.click();
        else
            //document.location.replace(url);
            window.location.href = url;
    },
    do_send_info:function (data){//返回父窗口
    	if(data['callback']){
    		var callback = data['callback'];
    		Utils.dynamicInvocationMethod(callback, data['data']);
    	}
    },
    dynamicInvocationMethod:function(mehodName,params){
    	 return eval(mehodName)(params)
    },
    //----------------------------------------
    // 图片相关
    //----------------------------------------

    ImageAutoScaling: function (iw, ih, mw, mh) {
        var size = { width: 0, height: 0 };
        if (true) {
            if (iw > 0 && ih > 0) {
                if (iw / ih >= mw / mh) {
                    if (iw > mw) {
                        size.width = (mw);
                        size.height = ((ih * mw) / iw);
                    } else {
                        size.width = (iw);
                        size.height = (ih);
                    }
                }
                else {
                    if (ih > mh) {
                        size.height = (mh);
                        size.width = ((iw * mh) / ih);
                    } else {
                        size.width = (iw);
                        size.height = (ih);
                    }
                }
            }
        }
        return size;
    },

    //----------------------------------------
    // 表单相关
    //----------------------------------------

    TextareaCharCount: function (element, incept, maxN) {
        var str = "";
        var count = this.GetStrLen(element.value);

        str = "(";
        if (count > maxN) {
            str += "<font color=\"red\">" + count + "</font>";
        }
        else {
            str += count;
        }
        str += "/" + maxN + ")";
        incept.innerHTML = str;
    },

    NumInputText: function (obj, defaultValue) {

        obj = $(obj);

        if (obj.val() == "") obj.val(defaultValue);
        obj
    .focus(function () { var obj = $(this); if (obj.val() == defaultValue || obj.val() == defaultValue.toString()) obj.val(""); })
    .blur(function () { var obj = $(this); if (obj.val() == "" || obj.val() == defaultValue.toString()) obj.val(defaultValue.toString()); });


    },

    //----------------------------------------
    // 数字运算
    //----------------------------------------

    AccAdd: function (arg1, arg2) {
        ///	<summary>
        ///	精确加法运算
        ///	</summary>
        var r1, r2, m;
        try { r1 = arg1.toString().split(".")[1].length } catch (e) { r1 = 0 }
        try { r2 = arg2.toString().split(".")[1].length } catch (e) { r2 = 0 }
        m = Math.pow(10, Math.max(r1, r2))
        //last modify by deeka
        //动态控制精度长度
        n = (r1 >= r2) ? r1 : r2;
        return Number(((arg1 * m + arg2 * m) / m).toFixed(n));
    },

    AccSub: function (arg1, arg2) {
        ///	<summary>
        ///	精确减法运算
        ///	</summary>
        var r1, r2, m, n;
        try { r1 = arg1.toString().split(".")[1].length } catch (e) { r1 = 0 }
        try { r2 = arg2.toString().split(".")[1].length } catch (e) { r2 = 0 }
        m = Math.pow(10, Math.max(r1, r2));
        //last modify by deeka
        //动态控制精度长度
        n = (r1 >= r2) ? r1 : r2;
        return Number(((arg2 * m - arg1 * m) / m).toFixed(n));
    },

    AccMul: function (arg1, arg2) {
        ///	<summary>
        ///	精确乘法运算
        ///	</summary>
        var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
        try { m += s1.split(".")[1].length } catch (e) { }
        try { m += s2.split(".")[1].length } catch (e) { }
        return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m);
    },

    AccDiv: function (arg1, arg2) {
        ///	<summary>
        ///	精确除法运算
        ///	</summary>
        var t1 = 0, t2 = 0, r1, r2;
        try { t1 = arg1.toString().split(".")[1].length } catch (e) { }
        try { t2 = arg2.toString().split(".")[1].length } catch (e) { }
        with (Math) {
            r1 = Number(arg1.toString().replace(".", ""));
            r2 = Number(arg2.toString().replace(".", ""));
            return Number((r1 / r2) * pow(10, t2 - t1));
        }
    },

    //----------------------------------------
    // 只能输入数字
    //----------------------------------------

    OnlyNumber: function (e) {

        // 只能输入数字 onkeypress="return Admin.OnlyNumber(event)"

        var isIE = false, isFF = false, isSa = false;

        if ((navigator.userAgent.indexOf("MSIE") > 0) && (parseInt(navigator.appVersion) >= 4)) isIE = true;

        if (navigator.userAgent.indexOf("Firefox") > 0) isFF = true;

        if (navigator.userAgent.indexOf("Safari") > 0) isSa = true;

        var key;

        iKeyCode = window.event ? e.keyCode : e.which;

        if (!(((iKeyCode >= 48) && (iKeyCode <= 57)))) {
            if (isIE)
                e.returnValue = false;
            else
                e.preventDefault();
        }
    },

    OnlyNumber2: function (e) {

        // 只能输入数字 onkeypress="return Admin.OnlyNumber(event)"

        var isIE = false, isFF = false, isSa = false;

        if ((navigator.userAgent.indexOf("MSIE") > 0) && (parseInt(navigator.appVersion) >= 4)) isIE = true;

        if (navigator.userAgent.indexOf("Firefox") > 0) isFF = true;

        if (navigator.userAgent.indexOf("Safari") > 0) isSa = true;

        var key;

        iKeyCode = window.event ? e.keyCode : e.which;

        if (!((((iKeyCode >= 48) && (iKeyCode <= 57)) || iKeyCode == 46))) {
            if (isIE)
                e.returnValue = false;
            else
                e.preventDefault();
        }
    },

    OnlyNumber3: function (e) {

        // 只能输入数字 onkeypress="return Admin.OnlyNumber(event)" 正负整数

        var isIE = false, isFF = false, isSa = false;

        if ((navigator.userAgent.indexOf("MSIE") > 0) && (parseInt(navigator.appVersion) >= 4)) isIE = true;

        if (navigator.userAgent.indexOf("Firefox") > 0) isFF = true;

        if (navigator.userAgent.indexOf("Safari") > 0) isSa = true;

        var key;

        iKeyCode = window.event ? e.keyCode : e.which;

        if (!((((iKeyCode >= 48) && (iKeyCode <= 57)) || iKeyCode == 45))) {

            if (isIE)
                e.returnValue = false;
            else

                e.preventDefault();
        }
    },

    IsFormChanged: function (el, filter) {

        el = document.getElementById(el);

        filter = filter || function (el) { return false; };

        var els = el.elements, l = els.length, i = 0, j = 0, el, opts;

        for (; i < l; ++i, j = 0) {
            el = els[i];

            switch (el.type) {
                case "text":
                case "hidden":
                case "password":
                case "textarea":
                    if (filter(el)) break;
                    if (el.defaultValue != el.value) return true;
                    break;
                case "radio":
                case "checkbox":
                    if (filter(el)) break;
                    if (el.defaultChecked != el.checked) return true;
                    break;
                case "select-one":
                    j = 1;
                case "select-multiple":
                    if (filter(el)) break;
                    opts = el.options;
                    for (; j < opts.length; ++j) {
                        if (opts[j].defaultSelected != opts[j].selected) return true;
                    }
                    break;
            }
        }

        return false;
    },
	
	//列表操作行为相关
	toggle: function(obj, act, id){
	 	var val = ($(obj).hasClass('label-success')) ? 0 : 1;

	    // 获得JSON数据
   		var res = this.AjaxResult(act, {val: val, id: id});

		// 显示错误信息提示
		if (res.status !== 200) { Union.ShowAjaxResultDialog(res); return; }
		
		$(obj).text(val?"启用":"禁用");
		$(obj).toggleClass("label-success");
		$(obj).toggleClass("label-danger");
		
}
}

// --------------------------------------------------------------------------------------------------- 字符串格式化 Begin
String.prototype.format = function () {
    var args = arguments;
    return this.replace(/\{(\d+)\}/g,
        function (m, i) {
            return args[i];
        });
}

String.format = function () {
    if (arguments.length == 0)
        return null;

    var str = arguments[0];
    for (var i = 1; i < arguments.length; i++) {
        var re = new RegExp('\\{' + (i - 1) + '\\}', 'gm');
        str = str.replace(re, arguments[i]);
    }
    return str;
}
// --------------------------------------------------------------------------------------------------- 字符串格式化 End

// --------------------------------------------------------------------------------------------------- 浮点运算
// 加法函数
Number.prototype.add = function (arg) {
    return Utils.AccAdd(arg, this);
}
// 减法函数
Number.prototype.sub = function (arg) {
    return Utils.AccSub(arg, this);
}
// 乘法函数
Number.prototype.mul = function (arg) {
    return Utils.AccMul(arg, this);
};
// 除法函数
Number.prototype.div = function (arg) {
    return Utils.AccDiv(this, arg);
};
// --------------------------------------------------------------------------------------------------- 浮点运算

// --------------------------------------------------------------------------------------------------- 两个字符串的相似度 Begin
// 例：Levenshtein_Distance_Percent(s.value, t.value);

function Levenshtein_Distance(s, t) {
    var n = s.length; // length of s
    var m = t.length; // length of t
    var d = []; // matrix
    var i; // iterates through s
    var j; // iterates through t
    var s_i; // ith character of s
    var t_j; // jth character of t
    var cost; // cost

    // Step 1

    if (n == 0) return m;
    if (m == 0) return n;

    // Step 2

    for (i = 0; i <= n; i++) {
        d[i] = [];
        d[i][0] = i;
    }

    for (j = 0; j <= m; j++) {
        d[0][j] = j;
    }

    // Step 3

    for (i = 1; i <= n; i++) {

        s_i = s.charAt(i - 1);

        // Step 4

        for (j = 1; j <= m; j++) {

            t_j = t.charAt(j - 1);

            // Step 5

            if (s_i == t_j) {
                cost = 0;
            } else {
                cost = 1;
            }

            // Step 6

            d[i][j] = Levenshtein_Minimum(d[i - 1][j] + 1, d[i][j - 1] + 1, d[i - 1][j - 1] + cost);
        }
    }

    // Step 7

    return d[n][m];
}

//求两个字符串的相似度,返回相似度百分比
function Levenshtein_Distance_Percent(s, t) {
    var l = s.length > t.length ? s.length : t.length;
    var d = Levenshtein_Distance(s, t);
    return (1 - d / l).toFixed(4);
}

//求三个数字中的最小值
function Levenshtein_Minimum(a, b, c) {
    return a < b ? (a < c ? a : c) : (b < c ? b : c);
}
// --------------------------------------------------------------------------------------------------- 两个字符串的相似度 End

// --------------------------------------------------------------------------------------------------- $日历插件显示中文参数 Begin
//$(function (a) {
//    a.datepicker.regional["zh-CN"] = {
//        closeText: "\u5173\u95ed", prevText: "&#x3c;\u4e0a\u6708", nextText: "\u4e0b\u6708&#x3e;", currentText: "\u4eca\u5929", monthNames: ["\u4e00\u6708", "\u4e8c\u6708", "\u4e09\u6708", "\u56db\u6708", "\u4e94\u6708", "\u516d\u6708", "\u4e03\u6708", "\u516b\u6708", "\u4e5d\u6708", "\u5341\u6708", "\u5341\u4e00\u6708", "\u5341\u4e8c\u6708"], monthNamesShort: ["\u4e00", "\u4e8c", "\u4e09", "\u56db", "\u4e94", "\u516d", "\u4e03", "\u516b", "\u4e5d", "\u5341", "\u5341\u4e00", "\u5341\u4e8c"],
//        dayNames: ["\u661f\u671f\u65e5", "\u661f\u671f\u4e00", "\u661f\u671f\u4e8c", "\u661f\u671f\u4e09", "\u661f\u671f\u56db", "\u661f\u671f\u4e94", "\u661f\u671f\u516d"], dayNamesShort: ["\u5468\u65e5", "\u5468\u4e00", "\u5468\u4e8c", "\u5468\u4e09", "\u5468\u56db", "\u5468\u4e94", "\u5468\u516d"], dayNamesMin: ["\u65e5", "\u4e00", "\u4e8c", "\u4e09", "\u56db", "\u4e94", "\u516d"], weekHeader: "\u5468", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: false, showMonthAfterYear: true, yearSuffix: "\u5e74"
//    }; a.datepicker.setDefaults(a.datepicker.regional["zh-CN"])
//});
// --------------------------------------------------------------------------------------------------- $日历插件显示中文参数 End

// --------------------------------------------------------------------------------------------------- json2.js Bengin
// http://www.JSON.org/json2.js
// 2010-11-17
// See http://www.JSON.org/js.html

if (!this.JSON) {
    this.JSON = {};
}

(function () {
    "use strict";

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf()) ?
                   this.getUTCFullYear() + '-' +
                 f(this.getUTCMonth() + 1) + '-' +
                 f(this.getUTCDate()) + 'T' +
                 f(this.getUTCHours()) + ':' +
                 f(this.getUTCMinutes()) + ':' +
                 f(this.getUTCSeconds()) + 'Z' : null;
        };

        String.prototype.toJSON =
        Number.prototype.toJSON =
        Boolean.prototype.toJSON = function (key) {
            return this.valueOf();
        };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"': '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

        escapable.lastIndex = 0;
        return escapable.test(string) ?
            '"' + string.replace(escapable, function (a) {
                var c = meta[a];
                return typeof c === 'string' ? c :
                    '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
            }) + '"' :
            '"' + string + '"';
    }


    function str(key, holder) {

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

        switch (typeof value) {
            case 'string':
                return quote(value);

            case 'number':

                return isFinite(value) ? String(value) : 'null';

            case 'boolean':
            case 'null':

                return String(value);

            case 'object':

                if (!value) {
                    return 'null';
                }

                gap += indent;
                partial = [];

                if (Object.prototype.toString.apply(value) === '[object Array]') {

                    length = value.length;
                    for (i = 0; i < length; i += 1) {
                        partial[i] = str(i, value) || 'null';
                    }

                    v = partial.length === 0 ? '[]' :
                    gap ? '[\n' + gap +
                            partial.join(',\n' + gap) + '\n' +
                                mind + ']' :
                          '[' + partial.join(',') + ']';
                    gap = mind;
                    return v;
                }

                if (rep && typeof rep === 'object') {
                    length = rep.length;
                    for (i = 0; i < length; i += 1) {
                        k = rep[i];
                        if (typeof k === 'string') {
                            v = str(k, value);
                            if (v) {
                                partial.push(quote(k) + (gap ? ': ' : ':') + v);
                            }
                        }
                    }
                } else {

                    for (k in value) {
                        if (Object.hasOwnProperty.call(value, k)) {
                            v = str(k, value);
                            if (v) {
                                partial.push(quote(k) + (gap ? ': ' : ':') + v);
                            }
                        }
                    }
                }

                v = partial.length === 0 ? '{}' :
                gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' +
                        mind + '}' : '{' + partial.join(',') + '}';
                gap = mind;
                return v;
        }
    }

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

            var i;
            gap = '';
            indent = '';

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

            } else if (typeof space === 'string') {
                indent = space;
            }

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                     typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

            return str('', { '': value });
        };
    }

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

            var j;

            function walk(holder, key) {

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

            if (/^[\],:{}\s]*$/
.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
.replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                j = eval('(' + text + ')');

                return typeof reviver === 'function' ?
                    walk({ '': j }, '') : j;
            }

            throw new SyntaxError('JSON.parse');
        };
    }
}());
// --------------------------------------------------------------------------------------------------- json2.js End
//---------------------------------------------------------------------------------------------------- jQuery Cookie Begin
/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (value !== undefined && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setTime(+t + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {};

		// To prevent the for loop in the first place assign an empty array
		// in case there are no cookies at all. Also prevents odd result when
		// calling $.cookie().
		var cookies = document.cookie ? document.cookie.split('; ') : [];

		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}

		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));
//---------------------------------------------------------------------------------------------------- jQuery Cookie End

//----------------------------------------------------------------------------------------------------  全局事件绑定
$(document).ready(function(){
	$('body').on('click','.popup',function(){
		var url = $(this).attr('href');
		 Utils.OpenCenterWin(url); 
		return false;
	})
})