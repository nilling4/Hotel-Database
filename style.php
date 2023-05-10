<?php
header("Content-type: text/css");


$font_family = 'Arial, Helvetica, sans-serif';
$font_size = '0.7em';
$border = '1px solid';

?>

table, td, th {
border-collapse: collapse;

margin-left: auto;
margin-right: auto;
}

table {
margin-bottom: 20px;
}


.body-container {
display: flex;
flex-direction: row;
align-items: center;
justify-content: center;
}



tr, th {
border: 1px solid #ddd;

}

tr:nth-child(even){background-color: #f2f2f2;}

tr {
text-align: center;
}
tr:hover {background-color: #ddd;}

th {
background-color: #ddd;
font-size: 20px;
padding-left: 10px;
padding-right: 10px;

}

.page {
background-color: #FFFFFF;
margin: 15px;
display: flex;
align-items: center;
flex-direction: column;
padding-left: 20px;
padding-right: 20px;
border-radius: 15px;
padding-top: 20px;
}

.pageupdate {
  background-color: #FFFFFF;
margin: 20px;
display: flex;
align-items: center;
flex-direction: row;
padding-left: 40px;
padding-right: 40px;
border-radius: 15px;
padding-top: 20px;
}

update {
margin: 20px;
display: flex;
flex-direction: column;
padding-left: 40px;
padding-right: 40px;
border-radius: 15px;
}

body {
display: flex;
flex-direction: column;
align-items: center;
background-color: #3a3839 !important;
border-radius: 5px;
}

* {
font-family: <?= $font_family ?>;
}

hr {
}

.formfield {

}

br {
line-height: 10px;
}

form {
display:flex;
align-items: center;
flex-direction: column;
}

h1 {
padding-top: 10px;
text-align: center;
color: #FFFFFF !important;
}

h2 {
margin-bottom: 10px;
padding-left: 20px;
padding-right: 20px;

}

h3 {
margin-bottom: 10px;
padding-top: 20px;

padding-left: 20px;
padding-right: 20px;

}




.attribute {
margin-top: 2.5px;
margin-bottom: 2.5px;
}

.text-success, .text-danger {
font-size: 20px;
padding: 20px;
}

.results {
background-color: #FFFFFF;
display: flex;
flex-direction: column;
align-items: center;
border-radius: 15px;
padding-top: 20px;
}

a {
text-decoration: none;

}

a:hover {
text-decoration: none;
}

.roworder {
display: flex;
flex-direction: row;
}

select {
margin-right: 10px;
}

input[type="text"] {
height: 30px;
}