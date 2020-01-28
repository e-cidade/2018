#!/bin/bash
#
#     E-cidade Software Publico para Gestao Municipal                
#  Copyright (C) 2009  DBselller Servicos de Informatica             
#                            www.dbseller.com.br                     
#                         e-cidade@dbseller.com.br                   
#                                                                    
#  Este programa e software livre; voce pode redistribui-lo e/ou     
#  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
#  publicada pela Free Software Foundation; tanto a versao 2 da      
#  Licenca como (a seu criterio) qualquer versao mais nova.          
#                                                                    
#  Este programa e distribuido na expectativa de ser util, mas SEM   
#  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
#  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
#  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
#  detalhes.                                                         
#                                                                    
#  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
#  junto com este programa; se nao, escreva para a Free Software     
#  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
#  02111-1307, USA.                                                  
#  
#  Copia da licenca no diretorio licenca/licenca_en.txt 
#                                licenca/licenca_pt.txt 
#

# Try to autodetect OOFFICE and OOOPYTHON.
OOFFICE=/usr/bin/soffice
OOOPYTHON=/usr/bin/python
BINPYTHONSCRIPT=bin/oo2pdf/

# Kill any running OpenOffice.org processes.
#killall -u `whoami` -q soffice

# Download the converter script if necessary.
# test -f DocumentConverter.py || wget http://www.artofsolving.com/files/DocumentConverter.py

OOPID=`pidof soffice.bin`

if [ "$OOPID" = "" ]
then
  # Start OpenOffice.org in listening mode on TCP port 8100.
  echo "Inciando OpenOffice como servico ..."
  
  # Comentado pois não estava funcionando com OO 2.4
  #$OOFFICE "-accept=socket,host=localhost,port=8100;urp;OpenOffice.ServiceManager" -norestore -nologo -headless &
  
  # Retirado do Site http://www.artofsolving.com/node/10
  $OOFFICE -accept="socket,host=localhost,port=8100;urp;" -nofirststartwizard -headless &

  # Wait a few seconds to be sure it has started.
  sleep 5s
fi

$OOOPYTHON ${BINPYTHONSCRIPT}DocumentConverter.py $1 $2

# Close OpenOffice.org.
#killall -u `whoami` soffice
