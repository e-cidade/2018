<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */
?>
#!/bin/bash
#http://www.oooninja.com/2008/02/batch-command-line-file-conversion-with.html
#apt-get install xvfb
# Try to autodetect OOFFICE and OOOPYTHON.
OOFFICE=/usr/bin/ooffice
OOOPYTHON=/usr/bin/python


# Set DISPLAY to something besides :1 (because :1 is the standard display). 
DISPLAY=:1000 
# Kill any existing virtual framebuffers. 
killall -u `whoami` Xvfb 
# Start the framebuffer. 
Xvfb $DISPLAY -screen 0 1024x768x24 &

# Kill any running OpenOffice.org processes.
killall -u `whoami` -q soffice

# Download the converter script if necessary.
#test -f DocumentConverter.py || wget http://www.artofsolving.com/files/DocumentConverter.py

# Start OpenOffice.org in listening mode on TCP port 8100.
$OOFFICE "-accept=socket,host=localhost,port=8100;urp;OpenOffice.ServiceManager" -norestore -nologo -headless -display :1000 &

# Wait a few seconds to be sure it has started.
sleep 5s

# Convert as many documents as you want serially (but not concurrently).
# Substitute whichever documents you wish.
#$OOOPYTHON DocumentConverter.py sample.ppt sample.swf
#$OOOPYTHON DocumentConverter.py sample.ppt sample.pdf
$OOOPYTHON DocumentConverter.py sample.odt sample.pdf
# Close OpenOffice.org.
killall -u `whoami` soffice