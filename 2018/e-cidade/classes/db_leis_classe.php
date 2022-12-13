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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE leis
class cl_leis { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $h08_codlei = 0; 
   var $h08_numero = null; 
   var $h08_dtlanc_dia = null; 
   var $h08_dtlanc_mes = null; 
   var $h08_dtlanc_ano = null; 
   var $h08_dtlanc = null; 
   var $h08_tipo = null; 
   var $h08_dtini_dia = null; 
   var $h08_dtini_mes = null; 
   var $h08_dtini_ano = null; 
   var $h08_dtini = null; 
   var $h08_dtfim_dia = null; 
   var $h08_dtfim_mes = null; 
   var $h08_dtfim_ano = null; 
   var $h08_dtfim = null; 
   var $h08_anos1 = 0; 
   var $h08_perc1 = 0; 
   var $h08_anos2 = 0; 
   var $h08_perc2 = 0; 
   var $h08_anos3 = 0; 
   var $h08_perc3 = 0; 
   var $h08_anos4 = 0; 
   var $h08_perc4 = 0; 
   var $h08_anos5 = 0; 
   var $h08_perc5 = 0; 
   var $h08_anos6 = 0; 
   var $h08_perc6 = 0; 
   var $h08_anos7 = 0; 
   var $h08_perc7 = 0; 
   var $h08_anos8 = 0; 
   var $h08_perc8 = 0; 
   var $h08_anos9 = 0; 
   var $h08_perc9 = 0; 
   var $h08_anos10 = 0; 
   var $h08_perc10 = 0; 
   var $h08_anos11 = 0; 
   var $h08_perc11 = 0; 
   var $h08_anos12 = 0; 
   var $h08_perc12 = 0; 
   var $h08_anos13 = 0; 
   var $h08_perc13 = 0; 
   var $h08_anos14 = 0; 
   var $h08_perc14 = 0; 
   var $h08_anos15 = 0; 
   var $h08_perc15 = 0; 
   var $h08_car1 = null; 
   var $h08_car2 = null; 
   var $h08_car3 = null; 
   var $h08_car4 = null; 
   var $h08_car5 = null; 
   var $h08_car6 = null; 
   var $h08_car7 = null; 
   var $h08_car8 = null; 
   var $h08_car9 = null; 
   var $h08_car10 = null; 
   var $h08_car11 = null; 
   var $h08_car12 = null; 
   var $h08_car13 = null; 
   var $h08_car14 = null; 
   var $h08_car15 = null; 
   var $h08_anos16 = 0; 
   var $h08_anos17 = 0; 
   var $h08_anos18 = 0; 
   var $h08_car16 = null; 
   var $h08_car17 = null; 
   var $h08_car18 = null; 
   var $h08_perc16 = 0; 
   var $h08_perc17 = 0; 
   var $h08_perc18 = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h08_codlei = int4 = Código da lei 
                 h08_numero = varchar(6) = Número da lei 
                 h08_dtlanc = date = Lançamento 
                 h08_tipo = varchar(1) = Código do Tipo 
                 h08_dtini = date = Data inicio da vigencia da lei 
                 h08_dtfim = date = Data fim de vigencia da lei 
                 h08_anos1 = int4 = Quant.anos - 1.avanco/gratif. 
                 h08_perc1 = float8 = Percentual - 1.avanco/gratif. 
                 h08_anos2 = int4 = Quant.anos - 2.avanco/gratif. 
                 h08_perc2 = float8 = Percentual - 2.avanco/gratif. 
                 h08_anos3 = int4 = Quant.anos - 3.avanco/gratif 
                 h08_perc3 = float8 = Percentual - 3.avanco/gratif 
                 h08_anos4 = int4 = Quant anos - 4.avanco/gratif. 
                 h08_perc4 = float8 = Percentual - 4.avanco/gratif. 
                 h08_anos5 = int4 = Quant.anos - 5.avanco/gratif 
                 h08_perc5 = float8 = Percentual - 5.avanco/gratif. 
                 h08_anos6 = int4 = Quant.anos - 6.avanco/gratif 
                 h08_perc6 = float8 = Percentual - 6.avanco/gratif. 
                 h08_anos7 = int4 = Quant.anos - 7.avanco/gratif 
                 h08_perc7 = float8 = Percentual - 7.avanco/gratif. 
                 h08_anos8 = int4 = Quant.anos - 8.avanco/gratif. 
                 h08_perc8 = float8 = Percentual - 8.avanco/gratif. 
                 h08_anos9 = int4 = Quant.anos - 9.avanco/gratif. 
                 h08_perc9 = float8 = Percentual - 9.avanco/gratif. 
                 h08_anos10 = int4 = Quant.anos - 10.avanco/gratif. 
                 h08_perc10 = float8 = Percentual - 10.avanco/gratif. 
                 h08_anos11 = int4 = Quant.anos - 11.avanco/gratif. 
                 h08_perc11 = float8 = Percentual - 11.avanco/gratif. 
                 h08_anos12 = int4 = Quant.anos - 12.avanco/gratif. 
                 h08_perc12 = float8 = Percentual - 12.avanco/gratif 
                 h08_anos13 = int4 = Quant.anos - 13.avanco/gratif. 
                 h08_perc13 = float8 = Percentual - 13.avanco/gratif. 
                 h08_anos14 = int4 = Quant.anos - 14.avanco/gratif. 
                 h08_perc14 = float8 = Percentual - 14.avanco/gratifi 
                 h08_anos15 = int4 = Quant.anos - 15.avanco/gratif. 
                 h08_perc15 = float8 = Percentual - 15.avanco/gratif. 
                 h08_car1 = varchar(3) = Informação adicional 
                 h08_car2 = varchar(3) = Informação adicional 
                 h08_car3 = varchar(3) = Informação adicional 
                 h08_car4 = varchar(3) = Informação adicional 
                 h08_car5 = varchar(3) = Informação adicional 
                 h08_car6 = varchar(3) = Informação adicional 
                 h08_car7 = char(     3) = Informacao Adicional 
                 h08_car8 = char(     3) = Informacao Adicional 
                 h08_car9 = char(     3) = Informacao Adicional 
                 h08_car10 = varchar(3) = Informação adicional 
                 h08_car11 = varchar(3) = Informação adicional 
                 h08_car12 = varchar(3) = Informação adicional 
                 h08_car13 = varchar(3) = Informação adicional 
                 h08_car14 = varchar(3) = Informação adicional 
                 h08_car15 = varchar(3) = Informação adicional 
                 h08_anos16 = int4 = Quant. Anos 
                 h08_anos17 = int4 = Quant. anos 
                 h08_anos18 = int4 = Quant. anos 
                 h08_car16 = varchar(3) = Informação Adic. 
                 h08_car17 = varchar(3) = Informação adicional 
                 h08_car18 = varchar(3) = Informação adicional 
                 h08_perc16 = float8 = Percentual 16o. 
                 h08_perc17 = float8 = Percentual 17o. 
                 h08_perc18 = float8 = Percentual 18o. 
                 ";
   //funcao construtor da classe 
   function cl_leis() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("leis"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->h08_codlei = ($this->h08_codlei == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_codlei"]:$this->h08_codlei);
       $this->h08_numero = ($this->h08_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_numero"]:$this->h08_numero);
       if($this->h08_dtlanc == ""){
         $this->h08_dtlanc_dia = ($this->h08_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtlanc_dia"]:$this->h08_dtlanc_dia);
         $this->h08_dtlanc_mes = ($this->h08_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtlanc_mes"]:$this->h08_dtlanc_mes);
         $this->h08_dtlanc_ano = ($this->h08_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtlanc_ano"]:$this->h08_dtlanc_ano);
         if($this->h08_dtlanc_dia != ""){
            $this->h08_dtlanc = $this->h08_dtlanc_ano."-".$this->h08_dtlanc_mes."-".$this->h08_dtlanc_dia;
         }
       }
       $this->h08_tipo = ($this->h08_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_tipo"]:$this->h08_tipo);
       if($this->h08_dtini == ""){
         $this->h08_dtini_dia = ($this->h08_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtini_dia"]:$this->h08_dtini_dia);
         $this->h08_dtini_mes = ($this->h08_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtini_mes"]:$this->h08_dtini_mes);
         $this->h08_dtini_ano = ($this->h08_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtini_ano"]:$this->h08_dtini_ano);
         if($this->h08_dtini_dia != ""){
            $this->h08_dtini = $this->h08_dtini_ano."-".$this->h08_dtini_mes."-".$this->h08_dtini_dia;
         }
       }
       if($this->h08_dtfim == ""){
         $this->h08_dtfim_dia = ($this->h08_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtfim_dia"]:$this->h08_dtfim_dia);
         $this->h08_dtfim_mes = ($this->h08_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtfim_mes"]:$this->h08_dtfim_mes);
         $this->h08_dtfim_ano = ($this->h08_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_dtfim_ano"]:$this->h08_dtfim_ano);
         if($this->h08_dtfim_dia != ""){
            $this->h08_dtfim = $this->h08_dtfim_ano."-".$this->h08_dtfim_mes."-".$this->h08_dtfim_dia;
         }
       }
       $this->h08_anos1 = ($this->h08_anos1 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos1"]:$this->h08_anos1);
       $this->h08_perc1 = ($this->h08_perc1 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc1"]:$this->h08_perc1);
       $this->h08_anos2 = ($this->h08_anos2 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos2"]:$this->h08_anos2);
       $this->h08_perc2 = ($this->h08_perc2 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc2"]:$this->h08_perc2);
       $this->h08_anos3 = ($this->h08_anos3 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos3"]:$this->h08_anos3);
       $this->h08_perc3 = ($this->h08_perc3 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc3"]:$this->h08_perc3);
       $this->h08_anos4 = ($this->h08_anos4 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos4"]:$this->h08_anos4);
       $this->h08_perc4 = ($this->h08_perc4 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc4"]:$this->h08_perc4);
       $this->h08_anos5 = ($this->h08_anos5 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos5"]:$this->h08_anos5);
       $this->h08_perc5 = ($this->h08_perc5 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc5"]:$this->h08_perc5);
       $this->h08_anos6 = ($this->h08_anos6 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos6"]:$this->h08_anos6);
       $this->h08_perc6 = ($this->h08_perc6 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc6"]:$this->h08_perc6);
       $this->h08_anos7 = ($this->h08_anos7 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos7"]:$this->h08_anos7);
       $this->h08_perc7 = ($this->h08_perc7 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc7"]:$this->h08_perc7);
       $this->h08_anos8 = ($this->h08_anos8 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos8"]:$this->h08_anos8);
       $this->h08_perc8 = ($this->h08_perc8 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc8"]:$this->h08_perc8);
       $this->h08_anos9 = ($this->h08_anos9 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos9"]:$this->h08_anos9);
       $this->h08_perc9 = ($this->h08_perc9 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc9"]:$this->h08_perc9);
       $this->h08_anos10 = ($this->h08_anos10 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos10"]:$this->h08_anos10);
       $this->h08_perc10 = ($this->h08_perc10 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc10"]:$this->h08_perc10);
       $this->h08_anos11 = ($this->h08_anos11 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos11"]:$this->h08_anos11);
       $this->h08_perc11 = ($this->h08_perc11 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc11"]:$this->h08_perc11);
       $this->h08_anos12 = ($this->h08_anos12 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos12"]:$this->h08_anos12);
       $this->h08_perc12 = ($this->h08_perc12 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc12"]:$this->h08_perc12);
       $this->h08_anos13 = ($this->h08_anos13 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos13"]:$this->h08_anos13);
       $this->h08_perc13 = ($this->h08_perc13 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc13"]:$this->h08_perc13);
       $this->h08_anos14 = ($this->h08_anos14 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos14"]:$this->h08_anos14);
       $this->h08_perc14 = ($this->h08_perc14 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc14"]:$this->h08_perc14);
       $this->h08_anos15 = ($this->h08_anos15 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos15"]:$this->h08_anos15);
       $this->h08_perc15 = ($this->h08_perc15 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc15"]:$this->h08_perc15);
       $this->h08_car1 = ($this->h08_car1 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car1"]:$this->h08_car1);
       $this->h08_car2 = ($this->h08_car2 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car2"]:$this->h08_car2);
       $this->h08_car3 = ($this->h08_car3 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car3"]:$this->h08_car3);
       $this->h08_car4 = ($this->h08_car4 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car4"]:$this->h08_car4);
       $this->h08_car5 = ($this->h08_car5 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car5"]:$this->h08_car5);
       $this->h08_car6 = ($this->h08_car6 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car6"]:$this->h08_car6);
       $this->h08_car7 = ($this->h08_car7 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car7"]:$this->h08_car7);
       $this->h08_car8 = ($this->h08_car8 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car8"]:$this->h08_car8);
       $this->h08_car9 = ($this->h08_car9 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car9"]:$this->h08_car9);
       $this->h08_car10 = ($this->h08_car10 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car10"]:$this->h08_car10);
       $this->h08_car11 = ($this->h08_car11 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car11"]:$this->h08_car11);
       $this->h08_car12 = ($this->h08_car12 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car12"]:$this->h08_car12);
       $this->h08_car13 = ($this->h08_car13 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car13"]:$this->h08_car13);
       $this->h08_car14 = ($this->h08_car14 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car14"]:$this->h08_car14);
       $this->h08_car15 = ($this->h08_car15 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car15"]:$this->h08_car15);
       $this->h08_anos16 = ($this->h08_anos16 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos16"]:$this->h08_anos16);
       $this->h08_anos17 = ($this->h08_anos17 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos17"]:$this->h08_anos17);
       $this->h08_anos18 = ($this->h08_anos18 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_anos18"]:$this->h08_anos18);
       $this->h08_car16 = ($this->h08_car16 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car16"]:$this->h08_car16);
       $this->h08_car17 = ($this->h08_car17 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car17"]:$this->h08_car17);
       $this->h08_car18 = ($this->h08_car18 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_car18"]:$this->h08_car18);
       $this->h08_perc16 = ($this->h08_perc16 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc16"]:$this->h08_perc16);
       $this->h08_perc17 = ($this->h08_perc17 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc17"]:$this->h08_perc17);
       $this->h08_perc18 = ($this->h08_perc18 == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_perc18"]:$this->h08_perc18);
     }else{
       $this->h08_codlei = ($this->h08_codlei == ""?@$GLOBALS["HTTP_POST_VARS"]["h08_codlei"]:$this->h08_codlei);
     }
   }
   // funcao para inclusao
   function incluir ($h08_codlei){ 
      $this->atualizacampos();
     if($this->h08_numero == null ){ 
       $this->erro_sql = " Campo Número da lei nao Informado.";
       $this->erro_campo = "h08_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h08_dtlanc == null ){ 
       $this->erro_sql = " Campo Lançamento nao Informado.";
       $this->erro_campo = "h08_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h08_tipo == null ){ 
       $this->erro_sql = " Campo Código do Tipo nao Informado.";
       $this->erro_campo = "h08_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h08_dtini == null ){ 
       $this->erro_sql = " Campo Data inicio da vigencia da lei nao Informado.";
       $this->erro_campo = "h08_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h08_anos1 == null ){ 
       $this->h08_anos1 = "0";
     }
     if($this->h08_perc1 == null ){ 
       $this->h08_perc1 = "0";
     }
     if($this->h08_anos2 == null ){ 
       $this->h08_anos2 = "0";
     }
     if($this->h08_perc2 == null ){ 
       $this->h08_perc2 = "0";
     }
     if($this->h08_anos3 == null ){ 
       $this->h08_anos3 = "0";
     }
     if($this->h08_perc3 == null ){ 
       $this->h08_perc3 = "0";
     }
     if($this->h08_anos4 == null ){ 
       $this->h08_anos4 = "0";
     }
     if($this->h08_perc4 == null ){ 
       $this->h08_perc4 = "0";
     }
     if($this->h08_anos5 == null ){ 
       $this->h08_anos5 = "0";
     }
     if($this->h08_perc5 == null ){ 
       $this->h08_perc5 = "0";
     }
     if($this->h08_anos6 == null ){ 
       $this->h08_anos6 = "0";
     }
     if($this->h08_perc6 == null ){ 
       $this->h08_perc6 = "0";
     }
     if($this->h08_anos7 == null ){ 
       $this->h08_anos7 = "0";
     }
     if($this->h08_perc7 == null ){ 
       $this->h08_perc7 = "0";
     }
     if($this->h08_anos8 == null ){ 
       $this->h08_anos8 = "0";
     }
     if($this->h08_perc8 == null ){ 
       $this->h08_perc8 = "0";
     }
     if($this->h08_anos9 == null ){ 
       $this->h08_anos9 = "0";
     }
     if($this->h08_perc9 == null ){ 
       $this->h08_perc9 = "0";
     }
     if($this->h08_anos10 == null ){ 
       $this->h08_anos10 = "0";
     }
     if($this->h08_perc10 == null ){ 
       $this->h08_perc10 = "0";
     }
     if($this->h08_anos11 == null ){ 
       $this->h08_anos11 = "0";
     }
     if($this->h08_perc11 == null ){ 
       $this->h08_perc11 = "0";
     }
     if($this->h08_anos12 == null ){ 
       $this->h08_anos12 = "0";
     }
     if($this->h08_perc12 == null ){ 
       $this->h08_perc12 = "0";
     }
     if($this->h08_anos13 == null ){ 
       $this->h08_anos13 = "0";
     }
     if($this->h08_perc13 == null ){ 
       $this->h08_perc13 = "0";
     }
     if($this->h08_anos14 == null ){ 
       $this->h08_anos14 = "0";
     }
     if($this->h08_perc14 == null ){ 
       $this->h08_perc14 = "0";
     }
     if($this->h08_anos15 == null ){ 
       $this->h08_anos15 = "0";
     }
     if($this->h08_perc15 == null ){ 
       $this->h08_perc15 = "0";
     }
     if($this->h08_anos16 == null ){ 
       $this->h08_anos16 = "0";
     }
     if($this->h08_anos17 == null ){ 
       $this->h08_anos17 = "0";
     }
     if($this->h08_anos18 == null ){ 
       $this->h08_anos18 = "0";
     }
     if($this->h08_perc16 == null ){ 
       $this->h08_perc16 = "0";
     }
     if($this->h08_perc17 == null ){ 
       $this->h08_perc17 = "0";
     }
     if($this->h08_perc18 == null ){ 
       $this->h08_perc18 = "0";
     }
     if($h08_codlei == "" || $h08_codlei == null ){
       $result = db_query("select nextval('leis_h08_codlei_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: leis_h08_codlei_seq do campo: h08_codlei"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h08_codlei = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from leis_h08_codlei_seq");
       if(($result != false) && (pg_result($result,0,0) < $h08_codlei)){
         $this->erro_sql = " Campo h08_codlei maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h08_codlei = $h08_codlei; 
       }
     }
     if(($this->h08_codlei == null) || ($this->h08_codlei == "") ){ 
       $this->erro_sql = " Campo h08_codlei nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into leis(
                                       h08_codlei 
                                      ,h08_numero 
                                      ,h08_dtlanc 
                                      ,h08_tipo 
                                      ,h08_dtini 
                                      ,h08_dtfim 
                                      ,h08_anos1 
                                      ,h08_perc1 
                                      ,h08_anos2 
                                      ,h08_perc2 
                                      ,h08_anos3 
                                      ,h08_perc3 
                                      ,h08_anos4 
                                      ,h08_perc4 
                                      ,h08_anos5 
                                      ,h08_perc5 
                                      ,h08_anos6 
                                      ,h08_perc6 
                                      ,h08_anos7 
                                      ,h08_perc7 
                                      ,h08_anos8 
                                      ,h08_perc8 
                                      ,h08_anos9 
                                      ,h08_perc9 
                                      ,h08_anos10 
                                      ,h08_perc10 
                                      ,h08_anos11 
                                      ,h08_perc11 
                                      ,h08_anos12 
                                      ,h08_perc12 
                                      ,h08_anos13 
                                      ,h08_perc13 
                                      ,h08_anos14 
                                      ,h08_perc14 
                                      ,h08_anos15 
                                      ,h08_perc15 
                                      ,h08_car1 
                                      ,h08_car2 
                                      ,h08_car3 
                                      ,h08_car4 
                                      ,h08_car5 
                                      ,h08_car6 
                                      ,h08_car7 
                                      ,h08_car8 
                                      ,h08_car9 
                                      ,h08_car10 
                                      ,h08_car11 
                                      ,h08_car12 
                                      ,h08_car13 
                                      ,h08_car14 
                                      ,h08_car15 
                                      ,h08_anos16 
                                      ,h08_anos17 
                                      ,h08_anos18 
                                      ,h08_car16 
                                      ,h08_car17 
                                      ,h08_car18 
                                      ,h08_perc16 
                                      ,h08_perc17 
                                      ,h08_perc18 
                       )
                values (
                                $this->h08_codlei 
                               ,'$this->h08_numero' 
                               ,".($this->h08_dtlanc == "null" || $this->h08_dtlanc == ""?"null":"'".$this->h08_dtlanc."'")." 
                               ,'$this->h08_tipo' 
                               ,".($this->h08_dtini == "null" || $this->h08_dtini == ""?"null":"'".$this->h08_dtini."'")." 
                               ,".($this->h08_dtfim == "null" || $this->h08_dtfim == ""?"null":"'".$this->h08_dtfim."'")." 
                               ,$this->h08_anos1 
                               ,$this->h08_perc1 
                               ,$this->h08_anos2 
                               ,$this->h08_perc2 
                               ,$this->h08_anos3 
                               ,$this->h08_perc3 
                               ,$this->h08_anos4 
                               ,$this->h08_perc4 
                               ,$this->h08_anos5 
                               ,$this->h08_perc5 
                               ,$this->h08_anos6 
                               ,$this->h08_perc6 
                               ,$this->h08_anos7 
                               ,$this->h08_perc7 
                               ,$this->h08_anos8 
                               ,$this->h08_perc8 
                               ,$this->h08_anos9 
                               ,$this->h08_perc9 
                               ,$this->h08_anos10 
                               ,$this->h08_perc10 
                               ,$this->h08_anos11 
                               ,$this->h08_perc11 
                               ,$this->h08_anos12 
                               ,$this->h08_perc12 
                               ,$this->h08_anos13 
                               ,$this->h08_perc13 
                               ,$this->h08_anos14 
                               ,$this->h08_perc14 
                               ,$this->h08_anos15 
                               ,$this->h08_perc15 
                               ,'$this->h08_car1' 
                               ,'$this->h08_car2' 
                               ,'$this->h08_car3' 
                               ,'$this->h08_car4' 
                               ,'$this->h08_car5' 
                               ,'$this->h08_car6' 
                               ,'$this->h08_car7' 
                               ,'$this->h08_car8' 
                               ,'$this->h08_car9' 
                               ,'$this->h08_car10' 
                               ,'$this->h08_car11' 
                               ,'$this->h08_car12' 
                               ,'$this->h08_car13' 
                               ,'$this->h08_car14' 
                               ,'$this->h08_car15' 
                               ,$this->h08_anos16 
                               ,$this->h08_anos17 
                               ,$this->h08_anos18 
                               ,'$this->h08_car16' 
                               ,'$this->h08_car17' 
                               ,'$this->h08_car18' 
                               ,$this->h08_perc16 
                               ,$this->h08_perc17 
                               ,$this->h08_perc18 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de leis ($this->h08_codlei) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de leis já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de leis ($this->h08_codlei) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h08_codlei;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h08_codlei));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9521,'$this->h08_codlei','I')");
       $resac = db_query("insert into db_acount values($acount,572,9521,'','".AddSlashes(pg_result($resaco,0,'h08_codlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4135,'','".AddSlashes(pg_result($resaco,0,'h08_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4136,'','".AddSlashes(pg_result($resaco,0,'h08_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4137,'','".AddSlashes(pg_result($resaco,0,'h08_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4138,'','".AddSlashes(pg_result($resaco,0,'h08_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4139,'','".AddSlashes(pg_result($resaco,0,'h08_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4140,'','".AddSlashes(pg_result($resaco,0,'h08_anos1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4141,'','".AddSlashes(pg_result($resaco,0,'h08_perc1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4142,'','".AddSlashes(pg_result($resaco,0,'h08_anos2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4143,'','".AddSlashes(pg_result($resaco,0,'h08_perc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4144,'','".AddSlashes(pg_result($resaco,0,'h08_anos3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4145,'','".AddSlashes(pg_result($resaco,0,'h08_perc3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4146,'','".AddSlashes(pg_result($resaco,0,'h08_anos4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4147,'','".AddSlashes(pg_result($resaco,0,'h08_perc4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4148,'','".AddSlashes(pg_result($resaco,0,'h08_anos5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4149,'','".AddSlashes(pg_result($resaco,0,'h08_perc5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4150,'','".AddSlashes(pg_result($resaco,0,'h08_anos6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4151,'','".AddSlashes(pg_result($resaco,0,'h08_perc6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4152,'','".AddSlashes(pg_result($resaco,0,'h08_anos7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4153,'','".AddSlashes(pg_result($resaco,0,'h08_perc7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4154,'','".AddSlashes(pg_result($resaco,0,'h08_anos8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4155,'','".AddSlashes(pg_result($resaco,0,'h08_perc8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4156,'','".AddSlashes(pg_result($resaco,0,'h08_anos9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4157,'','".AddSlashes(pg_result($resaco,0,'h08_perc9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4158,'','".AddSlashes(pg_result($resaco,0,'h08_anos10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4159,'','".AddSlashes(pg_result($resaco,0,'h08_perc10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4160,'','".AddSlashes(pg_result($resaco,0,'h08_anos11'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4161,'','".AddSlashes(pg_result($resaco,0,'h08_perc11'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4162,'','".AddSlashes(pg_result($resaco,0,'h08_anos12'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4163,'','".AddSlashes(pg_result($resaco,0,'h08_perc12'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4164,'','".AddSlashes(pg_result($resaco,0,'h08_anos13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4165,'','".AddSlashes(pg_result($resaco,0,'h08_perc13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4166,'','".AddSlashes(pg_result($resaco,0,'h08_anos14'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4167,'','".AddSlashes(pg_result($resaco,0,'h08_perc14'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4168,'','".AddSlashes(pg_result($resaco,0,'h08_anos15'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4169,'','".AddSlashes(pg_result($resaco,0,'h08_perc15'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4170,'','".AddSlashes(pg_result($resaco,0,'h08_car1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4171,'','".AddSlashes(pg_result($resaco,0,'h08_car2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4172,'','".AddSlashes(pg_result($resaco,0,'h08_car3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4173,'','".AddSlashes(pg_result($resaco,0,'h08_car4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4174,'','".AddSlashes(pg_result($resaco,0,'h08_car5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4175,'','".AddSlashes(pg_result($resaco,0,'h08_car6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4176,'','".AddSlashes(pg_result($resaco,0,'h08_car7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4177,'','".AddSlashes(pg_result($resaco,0,'h08_car8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4178,'','".AddSlashes(pg_result($resaco,0,'h08_car9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4179,'','".AddSlashes(pg_result($resaco,0,'h08_car10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4180,'','".AddSlashes(pg_result($resaco,0,'h08_car11'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4181,'','".AddSlashes(pg_result($resaco,0,'h08_car12'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4182,'','".AddSlashes(pg_result($resaco,0,'h08_car13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4183,'','".AddSlashes(pg_result($resaco,0,'h08_car14'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4184,'','".AddSlashes(pg_result($resaco,0,'h08_car15'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4626,'','".AddSlashes(pg_result($resaco,0,'h08_anos16'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4627,'','".AddSlashes(pg_result($resaco,0,'h08_anos17'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4628,'','".AddSlashes(pg_result($resaco,0,'h08_anos18'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4633,'','".AddSlashes(pg_result($resaco,0,'h08_car16'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4634,'','".AddSlashes(pg_result($resaco,0,'h08_car17'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4635,'','".AddSlashes(pg_result($resaco,0,'h08_car18'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4629,'','".AddSlashes(pg_result($resaco,0,'h08_perc16'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4630,'','".AddSlashes(pg_result($resaco,0,'h08_perc17'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,572,4636,'','".AddSlashes(pg_result($resaco,0,'h08_perc18'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h08_codlei=null) { 
      $this->atualizacampos();
     $sql = " update leis set ";
     $virgula = "";
     if(trim($this->h08_codlei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_codlei"])){ 
       $sql  .= $virgula." h08_codlei = $this->h08_codlei ";
       $virgula = ",";
       if(trim($this->h08_codlei) == null ){ 
         $this->erro_sql = " Campo Código da lei nao Informado.";
         $this->erro_campo = "h08_codlei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h08_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_numero"])){ 
       $sql  .= $virgula." h08_numero = '$this->h08_numero' ";
       $virgula = ",";
       if(trim($this->h08_numero) == null ){ 
         $this->erro_sql = " Campo Número da lei nao Informado.";
         $this->erro_campo = "h08_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h08_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h08_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." h08_dtlanc = '$this->h08_dtlanc' ";
       $virgula = ",";
       if(trim($this->h08_dtlanc) == null ){ 
         $this->erro_sql = " Campo Lançamento nao Informado.";
         $this->erro_campo = "h08_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h08_dtlanc_dia"])){ 
         $sql  .= $virgula." h08_dtlanc = null ";
         $virgula = ",";
         if(trim($this->h08_dtlanc) == null ){ 
           $this->erro_sql = " Campo Lançamento nao Informado.";
           $this->erro_campo = "h08_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h08_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_tipo"])){ 
       $sql  .= $virgula." h08_tipo = '$this->h08_tipo' ";
       $virgula = ",";
       if(trim($this->h08_tipo) == null ){ 
         $this->erro_sql = " Campo Código do Tipo nao Informado.";
         $this->erro_campo = "h08_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h08_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h08_dtini_dia"] !="") ){ 
       $sql  .= $virgula." h08_dtini = '$this->h08_dtini' ";
       $virgula = ",";
       if(trim($this->h08_dtini) == null ){ 
         $this->erro_sql = " Campo Data inicio da vigencia da lei nao Informado.";
         $this->erro_campo = "h08_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h08_dtini_dia"])){ 
         $sql  .= $virgula." h08_dtini = null ";
         $virgula = ",";
         if(trim($this->h08_dtini) == null ){ 
           $this->erro_sql = " Campo Data inicio da vigencia da lei nao Informado.";
           $this->erro_campo = "h08_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h08_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h08_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." h08_dtfim = '$this->h08_dtfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h08_dtfim_dia"])){ 
         $sql  .= $virgula." h08_dtfim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->h08_anos1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos1"])){ 
        if(trim($this->h08_anos1)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos1"])){ 
           $this->h08_anos1 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos1 = $this->h08_anos1 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc1"])){ 
        if(trim($this->h08_perc1)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc1"])){ 
           $this->h08_perc1 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc1 = $this->h08_perc1 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos2"])){ 
        if(trim($this->h08_anos2)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos2"])){ 
           $this->h08_anos2 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos2 = $this->h08_anos2 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc2"])){ 
        if(trim($this->h08_perc2)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc2"])){ 
           $this->h08_perc2 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc2 = $this->h08_perc2 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos3"])){ 
        if(trim($this->h08_anos3)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos3"])){ 
           $this->h08_anos3 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos3 = $this->h08_anos3 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc3"])){ 
        if(trim($this->h08_perc3)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc3"])){ 
           $this->h08_perc3 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc3 = $this->h08_perc3 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos4"])){ 
        if(trim($this->h08_anos4)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos4"])){ 
           $this->h08_anos4 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos4 = $this->h08_anos4 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc4"])){ 
        if(trim($this->h08_perc4)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc4"])){ 
           $this->h08_perc4 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc4 = $this->h08_perc4 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos5"])){ 
        if(trim($this->h08_anos5)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos5"])){ 
           $this->h08_anos5 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos5 = $this->h08_anos5 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc5"])){ 
        if(trim($this->h08_perc5)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc5"])){ 
           $this->h08_perc5 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc5 = $this->h08_perc5 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos6"])){ 
        if(trim($this->h08_anos6)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos6"])){ 
           $this->h08_anos6 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos6 = $this->h08_anos6 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc6"])){ 
        if(trim($this->h08_perc6)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc6"])){ 
           $this->h08_perc6 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc6 = $this->h08_perc6 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos7)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos7"])){ 
        if(trim($this->h08_anos7)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos7"])){ 
           $this->h08_anos7 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos7 = $this->h08_anos7 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc7)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc7"])){ 
        if(trim($this->h08_perc7)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc7"])){ 
           $this->h08_perc7 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc7 = $this->h08_perc7 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos8)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos8"])){ 
        if(trim($this->h08_anos8)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos8"])){ 
           $this->h08_anos8 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos8 = $this->h08_anos8 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc8)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc8"])){ 
        if(trim($this->h08_perc8)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc8"])){ 
           $this->h08_perc8 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc8 = $this->h08_perc8 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos9)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos9"])){ 
        if(trim($this->h08_anos9)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos9"])){ 
           $this->h08_anos9 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos9 = $this->h08_anos9 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc9)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc9"])){ 
        if(trim($this->h08_perc9)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc9"])){ 
           $this->h08_perc9 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc9 = $this->h08_perc9 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos10"])){ 
        if(trim($this->h08_anos10)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos10"])){ 
           $this->h08_anos10 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos10 = $this->h08_anos10 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc10"])){ 
        if(trim($this->h08_perc10)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc10"])){ 
           $this->h08_perc10 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc10 = $this->h08_perc10 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos11)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos11"])){ 
        if(trim($this->h08_anos11)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos11"])){ 
           $this->h08_anos11 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos11 = $this->h08_anos11 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc11)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc11"])){ 
        if(trim($this->h08_perc11)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc11"])){ 
           $this->h08_perc11 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc11 = $this->h08_perc11 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos12)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos12"])){ 
        if(trim($this->h08_anos12)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos12"])){ 
           $this->h08_anos12 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos12 = $this->h08_anos12 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc12)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc12"])){ 
        if(trim($this->h08_perc12)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc12"])){ 
           $this->h08_perc12 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc12 = $this->h08_perc12 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos13"])){ 
        if(trim($this->h08_anos13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos13"])){ 
           $this->h08_anos13 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos13 = $this->h08_anos13 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc13"])){ 
        if(trim($this->h08_perc13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc13"])){ 
           $this->h08_perc13 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc13 = $this->h08_perc13 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos14)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos14"])){ 
        if(trim($this->h08_anos14)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos14"])){ 
           $this->h08_anos14 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos14 = $this->h08_anos14 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc14)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc14"])){ 
        if(trim($this->h08_perc14)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc14"])){ 
           $this->h08_perc14 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc14 = $this->h08_perc14 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos15)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos15"])){ 
        if(trim($this->h08_anos15)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos15"])){ 
           $this->h08_anos15 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos15 = $this->h08_anos15 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc15)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc15"])){ 
        if(trim($this->h08_perc15)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc15"])){ 
           $this->h08_perc15 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc15 = $this->h08_perc15 ";
       $virgula = ",";
     }
     if(trim($this->h08_car1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car1"])){ 
       $sql  .= $virgula." h08_car1 = '$this->h08_car1' ";
       $virgula = ",";
     }
     if(trim($this->h08_car2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car2"])){ 
       $sql  .= $virgula." h08_car2 = '$this->h08_car2' ";
       $virgula = ",";
     }
     if(trim($this->h08_car3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car3"])){ 
       $sql  .= $virgula." h08_car3 = '$this->h08_car3' ";
       $virgula = ",";
     }
     if(trim($this->h08_car4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car4"])){ 
       $sql  .= $virgula." h08_car4 = '$this->h08_car4' ";
       $virgula = ",";
     }
     if(trim($this->h08_car5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car5"])){ 
       $sql  .= $virgula." h08_car5 = '$this->h08_car5' ";
       $virgula = ",";
     }
     if(trim($this->h08_car6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car6"])){ 
       $sql  .= $virgula." h08_car6 = '$this->h08_car6' ";
       $virgula = ",";
     }
     if(trim($this->h08_car7)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car7"])){ 
       $sql  .= $virgula." h08_car7 = '$this->h08_car7' ";
       $virgula = ",";
     }
     if(trim($this->h08_car8)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car8"])){ 
       $sql  .= $virgula." h08_car8 = '$this->h08_car8' ";
       $virgula = ",";
     }
     if(trim($this->h08_car9)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car9"])){ 
       $sql  .= $virgula." h08_car9 = '$this->h08_car9' ";
       $virgula = ",";
     }
     if(trim($this->h08_car10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car10"])){ 
       $sql  .= $virgula." h08_car10 = '$this->h08_car10' ";
       $virgula = ",";
     }
     if(trim($this->h08_car11)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car11"])){ 
       $sql  .= $virgula." h08_car11 = '$this->h08_car11' ";
       $virgula = ",";
     }
     if(trim($this->h08_car12)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car12"])){ 
       $sql  .= $virgula." h08_car12 = '$this->h08_car12' ";
       $virgula = ",";
     }
     if(trim($this->h08_car13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car13"])){ 
       $sql  .= $virgula." h08_car13 = '$this->h08_car13' ";
       $virgula = ",";
     }
     if(trim($this->h08_car14)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car14"])){ 
       $sql  .= $virgula." h08_car14 = '$this->h08_car14' ";
       $virgula = ",";
     }
     if(trim($this->h08_car15)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car15"])){ 
       $sql  .= $virgula." h08_car15 = '$this->h08_car15' ";
       $virgula = ",";
     }
     if(trim($this->h08_anos16)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos16"])){ 
        if(trim($this->h08_anos16)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos16"])){ 
           $this->h08_anos16 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos16 = $this->h08_anos16 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos17)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos17"])){ 
        if(trim($this->h08_anos17)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos17"])){ 
           $this->h08_anos17 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos17 = $this->h08_anos17 ";
       $virgula = ",";
     }
     if(trim($this->h08_anos18)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_anos18"])){ 
        if(trim($this->h08_anos18)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_anos18"])){ 
           $this->h08_anos18 = "0" ; 
        } 
       $sql  .= $virgula." h08_anos18 = $this->h08_anos18 ";
       $virgula = ",";
     }
     if(trim($this->h08_car16)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car16"])){ 
       $sql  .= $virgula." h08_car16 = '$this->h08_car16' ";
       $virgula = ",";
     }
     if(trim($this->h08_car17)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car17"])){ 
       $sql  .= $virgula." h08_car17 = '$this->h08_car17' ";
       $virgula = ",";
     }
     if(trim($this->h08_car18)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_car18"])){ 
       $sql  .= $virgula." h08_car18 = '$this->h08_car18' ";
       $virgula = ",";
     }
     if(trim($this->h08_perc16)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc16"])){ 
        if(trim($this->h08_perc16)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc16"])){ 
           $this->h08_perc16 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc16 = $this->h08_perc16 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc17)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc17"])){ 
        if(trim($this->h08_perc17)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc17"])){ 
           $this->h08_perc17 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc17 = $this->h08_perc17 ";
       $virgula = ",";
     }
     if(trim($this->h08_perc18)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h08_perc18"])){ 
        if(trim($this->h08_perc18)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h08_perc18"])){ 
           $this->h08_perc18 = "0" ; 
        } 
       $sql  .= $virgula." h08_perc18 = $this->h08_perc18 ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h08_codlei!=null){
       $sql .= " h08_codlei = $this->h08_codlei";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h08_codlei));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9521,'$this->h08_codlei','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_codlei"]))
           $resac = db_query("insert into db_acount values($acount,572,9521,'".AddSlashes(pg_result($resaco,$conresaco,'h08_codlei'))."','$this->h08_codlei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_numero"]))
           $resac = db_query("insert into db_acount values($acount,572,4135,'".AddSlashes(pg_result($resaco,$conresaco,'h08_numero'))."','$this->h08_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_dtlanc"]))
           $resac = db_query("insert into db_acount values($acount,572,4136,'".AddSlashes(pg_result($resaco,$conresaco,'h08_dtlanc'))."','$this->h08_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_tipo"]))
           $resac = db_query("insert into db_acount values($acount,572,4137,'".AddSlashes(pg_result($resaco,$conresaco,'h08_tipo'))."','$this->h08_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_dtini"]))
           $resac = db_query("insert into db_acount values($acount,572,4138,'".AddSlashes(pg_result($resaco,$conresaco,'h08_dtini'))."','$this->h08_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,572,4139,'".AddSlashes(pg_result($resaco,$conresaco,'h08_dtfim'))."','$this->h08_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos1"]))
           $resac = db_query("insert into db_acount values($acount,572,4140,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos1'))."','$this->h08_anos1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc1"]))
           $resac = db_query("insert into db_acount values($acount,572,4141,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc1'))."','$this->h08_perc1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos2"]))
           $resac = db_query("insert into db_acount values($acount,572,4142,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos2'))."','$this->h08_anos2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc2"]))
           $resac = db_query("insert into db_acount values($acount,572,4143,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc2'))."','$this->h08_perc2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos3"]))
           $resac = db_query("insert into db_acount values($acount,572,4144,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos3'))."','$this->h08_anos3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc3"]))
           $resac = db_query("insert into db_acount values($acount,572,4145,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc3'))."','$this->h08_perc3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos4"]))
           $resac = db_query("insert into db_acount values($acount,572,4146,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos4'))."','$this->h08_anos4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc4"]))
           $resac = db_query("insert into db_acount values($acount,572,4147,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc4'))."','$this->h08_perc4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos5"]))
           $resac = db_query("insert into db_acount values($acount,572,4148,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos5'))."','$this->h08_anos5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc5"]))
           $resac = db_query("insert into db_acount values($acount,572,4149,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc5'))."','$this->h08_perc5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos6"]))
           $resac = db_query("insert into db_acount values($acount,572,4150,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos6'))."','$this->h08_anos6',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc6"]))
           $resac = db_query("insert into db_acount values($acount,572,4151,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc6'))."','$this->h08_perc6',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos7"]))
           $resac = db_query("insert into db_acount values($acount,572,4152,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos7'))."','$this->h08_anos7',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc7"]))
           $resac = db_query("insert into db_acount values($acount,572,4153,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc7'))."','$this->h08_perc7',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos8"]))
           $resac = db_query("insert into db_acount values($acount,572,4154,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos8'))."','$this->h08_anos8',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc8"]))
           $resac = db_query("insert into db_acount values($acount,572,4155,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc8'))."','$this->h08_perc8',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos9"]))
           $resac = db_query("insert into db_acount values($acount,572,4156,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos9'))."','$this->h08_anos9',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc9"]))
           $resac = db_query("insert into db_acount values($acount,572,4157,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc9'))."','$this->h08_perc9',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos10"]))
           $resac = db_query("insert into db_acount values($acount,572,4158,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos10'))."','$this->h08_anos10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc10"]))
           $resac = db_query("insert into db_acount values($acount,572,4159,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc10'))."','$this->h08_perc10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos11"]))
           $resac = db_query("insert into db_acount values($acount,572,4160,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos11'))."','$this->h08_anos11',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc11"]))
           $resac = db_query("insert into db_acount values($acount,572,4161,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc11'))."','$this->h08_perc11',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos12"]))
           $resac = db_query("insert into db_acount values($acount,572,4162,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos12'))."','$this->h08_anos12',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc12"]))
           $resac = db_query("insert into db_acount values($acount,572,4163,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc12'))."','$this->h08_perc12',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos13"]))
           $resac = db_query("insert into db_acount values($acount,572,4164,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos13'))."','$this->h08_anos13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc13"]))
           $resac = db_query("insert into db_acount values($acount,572,4165,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc13'))."','$this->h08_perc13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos14"]))
           $resac = db_query("insert into db_acount values($acount,572,4166,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos14'))."','$this->h08_anos14',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc14"]))
           $resac = db_query("insert into db_acount values($acount,572,4167,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc14'))."','$this->h08_perc14',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos15"]))
           $resac = db_query("insert into db_acount values($acount,572,4168,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos15'))."','$this->h08_anos15',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc15"]))
           $resac = db_query("insert into db_acount values($acount,572,4169,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc15'))."','$this->h08_perc15',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car1"]))
           $resac = db_query("insert into db_acount values($acount,572,4170,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car1'))."','$this->h08_car1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car2"]))
           $resac = db_query("insert into db_acount values($acount,572,4171,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car2'))."','$this->h08_car2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car3"]))
           $resac = db_query("insert into db_acount values($acount,572,4172,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car3'))."','$this->h08_car3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car4"]))
           $resac = db_query("insert into db_acount values($acount,572,4173,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car4'))."','$this->h08_car4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car5"]))
           $resac = db_query("insert into db_acount values($acount,572,4174,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car5'))."','$this->h08_car5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car6"]))
           $resac = db_query("insert into db_acount values($acount,572,4175,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car6'))."','$this->h08_car6',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car7"]))
           $resac = db_query("insert into db_acount values($acount,572,4176,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car7'))."','$this->h08_car7',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car8"]))
           $resac = db_query("insert into db_acount values($acount,572,4177,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car8'))."','$this->h08_car8',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car9"]))
           $resac = db_query("insert into db_acount values($acount,572,4178,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car9'))."','$this->h08_car9',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car10"]))
           $resac = db_query("insert into db_acount values($acount,572,4179,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car10'))."','$this->h08_car10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car11"]))
           $resac = db_query("insert into db_acount values($acount,572,4180,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car11'))."','$this->h08_car11',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car12"]))
           $resac = db_query("insert into db_acount values($acount,572,4181,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car12'))."','$this->h08_car12',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car13"]))
           $resac = db_query("insert into db_acount values($acount,572,4182,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car13'))."','$this->h08_car13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car14"]))
           $resac = db_query("insert into db_acount values($acount,572,4183,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car14'))."','$this->h08_car14',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car15"]))
           $resac = db_query("insert into db_acount values($acount,572,4184,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car15'))."','$this->h08_car15',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos16"]))
           $resac = db_query("insert into db_acount values($acount,572,4626,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos16'))."','$this->h08_anos16',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos17"]))
           $resac = db_query("insert into db_acount values($acount,572,4627,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos17'))."','$this->h08_anos17',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_anos18"]))
           $resac = db_query("insert into db_acount values($acount,572,4628,'".AddSlashes(pg_result($resaco,$conresaco,'h08_anos18'))."','$this->h08_anos18',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car16"]))
           $resac = db_query("insert into db_acount values($acount,572,4633,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car16'))."','$this->h08_car16',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car17"]))
           $resac = db_query("insert into db_acount values($acount,572,4634,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car17'))."','$this->h08_car17',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_car18"]))
           $resac = db_query("insert into db_acount values($acount,572,4635,'".AddSlashes(pg_result($resaco,$conresaco,'h08_car18'))."','$this->h08_car18',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc16"]))
           $resac = db_query("insert into db_acount values($acount,572,4629,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc16'))."','$this->h08_perc16',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc17"]))
           $resac = db_query("insert into db_acount values($acount,572,4630,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc17'))."','$this->h08_perc17',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h08_perc18"]))
           $resac = db_query("insert into db_acount values($acount,572,4636,'".AddSlashes(pg_result($resaco,$conresaco,'h08_perc18'))."','$this->h08_perc18',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de leis nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h08_codlei;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de leis nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h08_codlei;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h08_codlei;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h08_codlei=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h08_codlei));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9521,'$h08_codlei','E')");
         $resac = db_query("insert into db_acount values($acount,572,9521,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_codlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4135,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4136,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4137,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4138,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4139,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4140,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4141,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4142,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4143,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4144,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4145,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4146,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4147,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4148,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4149,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4150,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4151,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4152,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4153,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4154,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4155,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4156,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4157,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4158,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4159,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4160,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos11'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4161,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc11'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4162,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos12'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4163,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc12'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4164,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4165,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4166,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos14'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4167,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc14'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4168,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos15'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4169,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc15'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4170,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4171,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4172,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4173,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4174,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4175,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4176,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4177,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4178,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car9'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4179,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4180,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car11'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4181,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car12'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4182,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4183,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car14'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4184,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car15'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4626,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos16'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4627,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos17'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4628,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_anos18'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4633,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car16'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4634,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car17'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4635,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_car18'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4629,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc16'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4630,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc17'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,572,4636,'','".AddSlashes(pg_result($resaco,$iresaco,'h08_perc18'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from leis
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h08_codlei != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h08_codlei = $h08_codlei ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de leis nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h08_codlei;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de leis nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h08_codlei;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h08_codlei;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:leis";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h08_codlei=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from leis ";
     $sql2 = "";
     if($dbwhere==""){
       if($h08_codlei!=null ){
         $sql2 .= " where leis.h08_codlei = $h08_codlei "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $h08_codlei=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from leis ";
     $sql2 = "";
     if($dbwhere==""){
       if($h08_codlei!=null ){
         $sql2 .= " where leis.h08_codlei = $h08_codlei "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>