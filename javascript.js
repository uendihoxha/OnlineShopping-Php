function showLeftMenu() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            xmlDoc = xhttp.responseXML;

            htmlOptions = "";
            menuoptions = xmlDoc.getElementsByTagName("Name");
            for (i = 0; i < menuoptions.length; i++) {
                htmlOptions += "<li><a href='"
                        + xmlDoc.getElementsByTagName("Link")[i].firstChild.nodeValue + "'>"
                        + xmlDoc.getElementsByTagName("Name")[i].firstChild.nodeValue + "</a></li>";
            }

            document.getElementById("ulLeftMenu").innerHTML = htmlOptions;
        }
    };

    xhttp.open("GET", "data/leftmenu.xml", true);
    xhttp.send();
}
function ShowContact() {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            xmlDoc = xhttp.responseXML;

            document.getElementById("CompanyName").innerHTML = xmlDoc.getElementsByTagName("CompanyName")[0].firstChild.nodeValue;
            document.getElementById("Address").innerHTML = xmlDoc.getElementsByTagName("Address")[0].firstChild.nodeValue;
            document.getElementById("PhoneNumber").innerHTML = xmlDoc.getElementsByTagName("PhoneNumber")[0].firstChild.nodeValue;
            document.getElementById("Email").innerHTML = xmlDoc.getElementsByTagName("Email")[0].firstChild.nodeValue;
        }
    };

    xhttp.open("GET", "data/contact.xml", true);
    xhttp.send();
}
function showFooterDetails() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            xmlDoc = xhttp.responseXML;
            authors = xmlDoc.getElementsByTagName("StudentName");
            authorsInformations = "";

            for (i = 0; i < authors.length; i++) {
                authorsInformations += "<span>" + GetAuthorByXMLDocAtIndex(xmlDoc, i).toString() + "</span> <br>";
            }

            document.getElementById("spanFooter").innerHTML = authorsInformations;
        }
    };

    xhttp.open("GET", "data/data.xml", true);
    xhttp.send();
}

function Author() {
    this.studentId = "";
    this.name = "";
    this.dob = "";
    this.className = "";
    this.coursework = "";
}
function Author(studentId, name, dob, className, coursework) {
    this.studentId = studentId;
    this.name = name;
    this.dob = dob;
    this.className = className;
    this.coursework = coursework;

    this.show = function () {
        console.log(this.name + ' ' + this.dob + ' ' + this.studentId + ' ' + this.className + ' ' + this.coursework);
    };

    this.toString = function () {
        infos = "";
        if (this.coursework === "Yes")
            infos += "<b>Student: </b>";
        infos += this.name + ' ' + this.dob + ' ';


        return (infos);
    };
}
function GetAuthorByXMLDocAtIndex(xmlDoc, index) {
    var author = null;
    studentId = xmlDoc.getElementsByTagName("StudentId")[index].firstChild.nodeValue;
    name = xmlDoc.getElementsByTagName("StudentName")[index].firstChild.nodeValue;
    dob = xmlDoc.getElementsByTagName("DoB")[index].firstChild.nodeValue;
    className = xmlDoc.getElementsByTagName("ClassName")[index].firstChild.nodeValue;
    coursework = xmlDoc.getElementsByTagName("Coursework")[index].firstChild.nodeValue;
    author = new Author(studentId, name, dob, className, coursework);

    return (author);
}


