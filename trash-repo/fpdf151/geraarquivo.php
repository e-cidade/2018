<?

$pdf->Output();

//$tmpfile = "rp".rand(1,10000)."_".time().".pdf";
//$pdf->Output("tmp".$tmpfile);


//$tmpfile = tempnam($HTTP_SERVER_VARS["DOCUMENT_ROOT"].'/tmp','tmppdf').'.pdf';
//$pdf->Output($HTTP_SERVER_VARS["DOCUMENT_ROOT"].'/tmp'.'/'.basename($tmpfile));
//------



//$pdf1->objpdf->Output($HTTP_SERVER_VARS["DOCUMENT_ROOT"].'/tmp'.'/'.basename($tmpfile));

//system("pdf2ps $tmpfile $tmpfile.ps;cat $tmpfile.ps | gs -sDEVICE=ljet4 -sOutputFile=$tmpfile.pcl -sPAPERSIZE=a4 - &>/dev/null;lpr-cups $tmpfile.pcl");
//echo "<script>location.href='http://$DB_SERVIDOR/tmp/".basename($tmpfile)."'</script>";
echo "<script>location.href='http://192.168.1.15/~dbpaulo/dbportal2/tmp/".$tmpfile."'</script>";
//echo  "<script>window.close();</script>";

?>
 
