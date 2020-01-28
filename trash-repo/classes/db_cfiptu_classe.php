<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: cadastro
//CLASSE DA ENTIDADE cfiptu
class cl_cfiptu { 
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
   var $j18_anousu = 0; 
   var $j18_vlrref = 0; 
   var $j18_dtoper_dia = null; 
   var $j18_dtoper_mes = null; 
   var $j18_dtoper_ano = null; 
   var $j18_dtoper = null; 
   var $j18_rterri = 0; 
   var $j18_rpredi = 0; 
   var $j18_vencim = 0; 
   var $j18_logradauto = 'f'; 
   var $j18_segundavia = 0; 
   var $j18_infla = null; 
   var $j18_utilizasetfisc = 'f'; 
   var $j18_testadanumero = 'f'; 
   var $j18_excconscalc = 'f'; 
   var $j18_textoprom = null; 
   var $j18_calcvenc = 0; 
   var $j18_utilizaloc = 'f'; 
   var $j18_permvenc = 0; 
   var $j18_utidadosdiver = 'f'; 
   var $j18_dadoscertisen = 0; 
   var $j18_formatsetor = 0; 
   var $j18_formatquadra = 0; 
   var $j18_formatlote = 0; 
   var $j18_utilpontos = 0; 
   var $j18_ordendent = 0; 
   var $j18_iptuhistisen = 0; 
   var $j18_db_sysfuncoes = 0; 
   var $j18_tipoisen = 0; 
   var $j18_perccorrepadrao = 0; 
   var $j18_templatecertidaoexitencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j18_anousu = int4 = Exerc�cio 
                 j18_vlrref = float8 = Valor refer�ncia 
                 j18_dtoper = date = Data Opera��o 
                 j18_rterri = int4 = Receita Territorial 
                 j18_rpredi = int4 = Receita Predial 
                 j18_vencim = int4 = Tabela de Vencimento 
                 j18_logradauto = bool = C�digo do Logradouro Autom�tico 
                 j18_segundavia = int4 = Segunda Via 
                 j18_infla = varchar(5) = codigo do inflator 
                 j18_utilizasetfisc = bool = Utiliza Setor FIscal 
                 j18_testadanumero = bool = Utiliza numero na testada 
                 j18_excconscalc = bool = Excluir constru��o do C�lculo 
                 j18_textoprom = varchar(20) = Texto Promitente 
                 j18_calcvenc = int4 = Perguntar vencimentos durante calculo 
                 j18_utilizaloc = bool = Utilizar informa��es de localiza��o 
                 j18_permvenc = int4 = Permite escolher vencimentos durante calculo 
                 j18_utidadosdiver = bool = Utiliza dados diversos no calculo 
                 j18_dadoscertisen = int4 = Dados da certid�o de isen��o 
                 j18_formatsetor = int4 = Permite digitar para setor 
                 j18_formatquadra = int4 = Permite digitar para quadra 
                 j18_formatlote = int4 = Permite digitar para o lote 
                 j18_utilpontos = int4 = Utilizar pontua��o por constru��o 
                 j18_ordendent = int4 = Ordem no endereco de entrega 
                 j18_iptuhistisen = int8 = C�digo do hist�rico de isen��o 
                 j18_db_sysfuncoes = int4 = C�digo Fun��o 
                 j18_tipoisen = int4 = Codigo da isen��o 
                 j18_perccorrepadrao = float8 = Percentual de corre��o 
                 j18_templatecertidaoexitencia = int4 = Documento Template 
                 ";
   //funcao construtor da classe 
   function cl_cfiptu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cfiptu"); 
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
       $this->j18_anousu = ($this->j18_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_anousu"]:$this->j18_anousu);
       $this->j18_vlrref = ($this->j18_vlrref == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_vlrref"]:$this->j18_vlrref);
       if($this->j18_dtoper == ""){
         $this->j18_dtoper_dia = ($this->j18_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_dtoper_dia"]:$this->j18_dtoper_dia);
         $this->j18_dtoper_mes = ($this->j18_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_dtoper_mes"]:$this->j18_dtoper_mes);
         $this->j18_dtoper_ano = ($this->j18_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_dtoper_ano"]:$this->j18_dtoper_ano);
         if($this->j18_dtoper_dia != ""){
            $this->j18_dtoper = $this->j18_dtoper_ano."-".$this->j18_dtoper_mes."-".$this->j18_dtoper_dia;
         }
       }
       $this->j18_rterri = ($this->j18_rterri == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_rterri"]:$this->j18_rterri);
       $this->j18_rpredi = ($this->j18_rpredi == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_rpredi"]:$this->j18_rpredi);
       $this->j18_vencim = ($this->j18_vencim == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_vencim"]:$this->j18_vencim);
       $this->j18_logradauto = ($this->j18_logradauto == "f"?@$GLOBALS["HTTP_POST_VARS"]["j18_logradauto"]:$this->j18_logradauto);
       $this->j18_segundavia = ($this->j18_segundavia == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_segundavia"]:$this->j18_segundavia);
       $this->j18_infla = ($this->j18_infla == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_infla"]:$this->j18_infla);
       $this->j18_utilizasetfisc = ($this->j18_utilizasetfisc == "f"?@$GLOBALS["HTTP_POST_VARS"]["j18_utilizasetfisc"]:$this->j18_utilizasetfisc);
       $this->j18_testadanumero = ($this->j18_testadanumero == "f"?@$GLOBALS["HTTP_POST_VARS"]["j18_testadanumero"]:$this->j18_testadanumero);
       $this->j18_excconscalc = ($this->j18_excconscalc == "f"?@$GLOBALS["HTTP_POST_VARS"]["j18_excconscalc"]:$this->j18_excconscalc);
       $this->j18_textoprom = ($this->j18_textoprom == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_textoprom"]:$this->j18_textoprom);
       $this->j18_calcvenc = ($this->j18_calcvenc == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_calcvenc"]:$this->j18_calcvenc);
       $this->j18_utilizaloc = ($this->j18_utilizaloc == "f"?@$GLOBALS["HTTP_POST_VARS"]["j18_utilizaloc"]:$this->j18_utilizaloc);
       $this->j18_permvenc = ($this->j18_permvenc == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_permvenc"]:$this->j18_permvenc);
       $this->j18_utidadosdiver = ($this->j18_utidadosdiver == "f"?@$GLOBALS["HTTP_POST_VARS"]["j18_utidadosdiver"]:$this->j18_utidadosdiver);
       $this->j18_dadoscertisen = ($this->j18_dadoscertisen == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_dadoscertisen"]:$this->j18_dadoscertisen);
       $this->j18_formatsetor = ($this->j18_formatsetor == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_formatsetor"]:$this->j18_formatsetor);
       $this->j18_formatquadra = ($this->j18_formatquadra == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_formatquadra"]:$this->j18_formatquadra);
       $this->j18_formatlote = ($this->j18_formatlote == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_formatlote"]:$this->j18_formatlote);
       $this->j18_utilpontos = ($this->j18_utilpontos == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_utilpontos"]:$this->j18_utilpontos);
       $this->j18_ordendent = ($this->j18_ordendent == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_ordendent"]:$this->j18_ordendent);
       $this->j18_iptuhistisen = ($this->j18_iptuhistisen == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_iptuhistisen"]:$this->j18_iptuhistisen);
       $this->j18_db_sysfuncoes = ($this->j18_db_sysfuncoes == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_db_sysfuncoes"]:$this->j18_db_sysfuncoes);
       $this->j18_tipoisen = ($this->j18_tipoisen == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_tipoisen"]:$this->j18_tipoisen);
       $this->j18_perccorrepadrao = ($this->j18_perccorrepadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_perccorrepadrao"]:$this->j18_perccorrepadrao);
       $this->j18_templatecertidaoexitencia = ($this->j18_templatecertidaoexitencia == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_templatecertidaoexitencia"]:$this->j18_templatecertidaoexitencia);
     }else{
       $this->j18_anousu = ($this->j18_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j18_anousu"]:$this->j18_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($j18_anousu){ 
      $this->atualizacampos();
     if($this->j18_vlrref == null ){ 
       $this->erro_sql = " Campo Valor refer�ncia nao Informado.";
       $this->erro_campo = "j18_vlrref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_dtoper == null ){ 
       $this->erro_sql = " Campo Data Opera��o nao Informado.";
       $this->erro_campo = "j18_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_rterri == null ){ 
       $this->erro_sql = " Campo Receita Territorial nao Informado.";
       $this->erro_campo = "j18_rterri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_rpredi == null ){ 
       $this->erro_sql = " Campo Receita Predial nao Informado.";
       $this->erro_campo = "j18_rpredi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_vencim == null ){ 
       $this->erro_sql = " Campo Tabela de Vencimento nao Informado.";
       $this->erro_campo = "j18_vencim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_logradauto == null ){ 
       $this->erro_sql = " Campo C�digo do Logradouro Autom�tico nao Informado.";
       $this->erro_campo = "j18_logradauto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_segundavia == null ){ 
       $this->erro_sql = " Campo Segunda Via nao Informado.";
       $this->erro_campo = "j18_segundavia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_infla == null ){ 
       $this->erro_sql = " Campo codigo do inflator nao Informado.";
       $this->erro_campo = "j18_infla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_utilizasetfisc == null ){ 
       $this->erro_sql = " Campo Utiliza Setor FIscal nao Informado.";
       $this->erro_campo = "j18_utilizasetfisc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_testadanumero == null ){ 
       $this->erro_sql = " Campo Utiliza numero na testada nao Informado.";
       $this->erro_campo = "j18_testadanumero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_excconscalc == null ){ 
       $this->erro_sql = " Campo Excluir constru��o do C�lculo nao Informado.";
       $this->erro_campo = "j18_excconscalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_calcvenc == null ){ 
       $this->erro_sql = " Campo Perguntar vencimentos durante calculo nao Informado.";
       $this->erro_campo = "j18_calcvenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_utilizaloc == null ){ 
       $this->erro_sql = " Campo Utilizar informa��es de localiza��o nao Informado.";
       $this->erro_campo = "j18_utilizaloc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_permvenc == null ){ 
       $this->erro_sql = " Campo Permite escolher vencimentos durante calculo nao Informado.";
       $this->erro_campo = "j18_permvenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_utidadosdiver == null ){ 
       $this->erro_sql = " Campo Utiliza dados diversos no calculo nao Informado.";
       $this->erro_campo = "j18_utidadosdiver";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_dadoscertisen == null ){ 
       $this->erro_sql = " Campo Dados da certid�o de isen��o nao Informado.";
       $this->erro_campo = "j18_dadoscertisen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_formatsetor == null ){ 
       $this->erro_sql = " Campo Permite digitar para setor nao Informado.";
       $this->erro_campo = "j18_formatsetor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_formatquadra == null ){ 
       $this->erro_sql = " Campo Permite digitar para quadra nao Informado.";
       $this->erro_campo = "j18_formatquadra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_formatlote == null ){ 
       $this->erro_sql = " Campo Permite digitar para o lote nao Informado.";
       $this->erro_campo = "j18_formatlote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_utilpontos == null ){ 
       $this->erro_sql = " Campo Utilizar pontua��o por constru��o nao Informado.";
       $this->erro_campo = "j18_utilpontos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_ordendent == null ){ 
       $this->erro_sql = " Campo Ordem no endereco de entrega nao Informado.";
       $this->erro_campo = "j18_ordendent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_iptuhistisen == null ){ 
       $this->erro_sql = " Campo C�digo do hist�rico de isen��o nao Informado.";
       $this->erro_campo = "j18_iptuhistisen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_db_sysfuncoes == null ){ 
       $this->erro_sql = " Campo C�digo Fun��o nao Informado.";
       $this->erro_campo = "j18_db_sysfuncoes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_tipoisen == null ){ 
       $this->erro_sql = " Campo Codigo da isen��o nao Informado.";
       $this->erro_campo = "j18_tipoisen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j18_perccorrepadrao == null ){ 
       $this->j18_perccorrepadrao = "0";
     }
     if($this->j18_templatecertidaoexitencia == null ){ 
       $this->j18_templatecertidaoexitencia = "0";
     }
       $this->j18_anousu = $j18_anousu; 
     if(($this->j18_anousu == null) || ($this->j18_anousu == "") ){ 
       $this->erro_sql = " Campo j18_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cfiptu(
                                       j18_anousu 
                                      ,j18_vlrref 
                                      ,j18_dtoper 
                                      ,j18_rterri 
                                      ,j18_rpredi 
                                      ,j18_vencim 
                                      ,j18_logradauto 
                                      ,j18_segundavia 
                                      ,j18_infla 
                                      ,j18_utilizasetfisc 
                                      ,j18_testadanumero 
                                      ,j18_excconscalc 
                                      ,j18_textoprom 
                                      ,j18_calcvenc 
                                      ,j18_utilizaloc 
                                      ,j18_permvenc 
                                      ,j18_utidadosdiver 
                                      ,j18_dadoscertisen 
                                      ,j18_formatsetor 
                                      ,j18_formatquadra 
                                      ,j18_formatlote 
                                      ,j18_utilpontos 
                                      ,j18_ordendent 
                                      ,j18_iptuhistisen 
                                      ,j18_db_sysfuncoes 
                                      ,j18_tipoisen 
                                      ,j18_perccorrepadrao 
                                      ,j18_templatecertidaoexitencia 
                       )
                values (
                                $this->j18_anousu 
                               ,$this->j18_vlrref 
                               ,".($this->j18_dtoper == "null" || $this->j18_dtoper == ""?"null":"'".$this->j18_dtoper."'")." 
                               ,$this->j18_rterri 
                               ,$this->j18_rpredi 
                               ,$this->j18_vencim 
                               ,'$this->j18_logradauto' 
                               ,$this->j18_segundavia 
                               ,'$this->j18_infla' 
                               ,'$this->j18_utilizasetfisc' 
                               ,'$this->j18_testadanumero' 
                               ,'$this->j18_excconscalc' 
                               ,'$this->j18_textoprom' 
                               ,$this->j18_calcvenc 
                               ,'$this->j18_utilizaloc' 
                               ,$this->j18_permvenc 
                               ,'$this->j18_utidadosdiver' 
                               ,$this->j18_dadoscertisen 
                               ,$this->j18_formatsetor 
                               ,$this->j18_formatquadra 
                               ,$this->j18_formatlote 
                               ,$this->j18_utilpontos 
                               ,$this->j18_ordendent 
                               ,$this->j18_iptuhistisen 
                               ,$this->j18_db_sysfuncoes 
                               ,$this->j18_tipoisen 
                               ,$this->j18_perccorrepadrao 
                               ,$this->j18_templatecertidaoexitencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros ($this->j18_anousu) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros ($this->j18_anousu) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j18_anousu;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j18_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,808,'$this->j18_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,153,808,'','".AddSlashes(pg_result($resaco,0,'j18_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,809,'','".AddSlashes(pg_result($resaco,0,'j18_vlrref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,810,'','".AddSlashes(pg_result($resaco,0,'j18_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,812,'','".AddSlashes(pg_result($resaco,0,'j18_rterri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,811,'','".AddSlashes(pg_result($resaco,0,'j18_rpredi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,813,'','".AddSlashes(pg_result($resaco,0,'j18_vencim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,7320,'','".AddSlashes(pg_result($resaco,0,'j18_logradauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,7415,'','".AddSlashes(pg_result($resaco,0,'j18_segundavia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,7623,'','".AddSlashes(pg_result($resaco,0,'j18_infla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,7870,'','".AddSlashes(pg_result($resaco,0,'j18_utilizasetfisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,7932,'','".AddSlashes(pg_result($resaco,0,'j18_testadanumero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,7979,'','".AddSlashes(pg_result($resaco,0,'j18_excconscalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,8646,'','".AddSlashes(pg_result($resaco,0,'j18_textoprom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,8754,'','".AddSlashes(pg_result($resaco,0,'j18_calcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,8756,'','".AddSlashes(pg_result($resaco,0,'j18_utilizaloc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,8810,'','".AddSlashes(pg_result($resaco,0,'j18_permvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,8980,'','".AddSlashes(pg_result($resaco,0,'j18_utidadosdiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,9139,'','".AddSlashes(pg_result($resaco,0,'j18_dadoscertisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,9542,'','".AddSlashes(pg_result($resaco,0,'j18_formatsetor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,9543,'','".AddSlashes(pg_result($resaco,0,'j18_formatquadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,9544,'','".AddSlashes(pg_result($resaco,0,'j18_formatlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,9762,'','".AddSlashes(pg_result($resaco,0,'j18_utilpontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,9856,'','".AddSlashes(pg_result($resaco,0,'j18_ordendent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,9858,'','".AddSlashes(pg_result($resaco,0,'j18_iptuhistisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,10824,'','".AddSlashes(pg_result($resaco,0,'j18_db_sysfuncoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,10831,'','".AddSlashes(pg_result($resaco,0,'j18_tipoisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,11059,'','".AddSlashes(pg_result($resaco,0,'j18_perccorrepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,153,18859,'','".AddSlashes(pg_result($resaco,0,'j18_templatecertidaoexitencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j18_anousu=null) { 
      $this->atualizacampos();
     $sql = " update cfiptu set ";
     $virgula = "";
     if(trim($this->j18_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_anousu"])){ 
       $sql  .= $virgula." j18_anousu = $this->j18_anousu ";
       $virgula = ",";
       if(trim($this->j18_anousu) == null ){ 
         $this->erro_sql = " Campo Exerc�cio nao Informado.";
         $this->erro_campo = "j18_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_vlrref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_vlrref"])){ 
       $sql  .= $virgula." j18_vlrref = $this->j18_vlrref ";
       $virgula = ",";
       if(trim($this->j18_vlrref) == null ){ 
         $this->erro_sql = " Campo Valor refer�ncia nao Informado.";
         $this->erro_campo = "j18_vlrref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j18_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." j18_dtoper = '$this->j18_dtoper' ";
       $virgula = ",";
       if(trim($this->j18_dtoper) == null ){ 
         $this->erro_sql = " Campo Data Opera��o nao Informado.";
         $this->erro_campo = "j18_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j18_dtoper_dia"])){ 
         $sql  .= $virgula." j18_dtoper = null ";
         $virgula = ",";
         if(trim($this->j18_dtoper) == null ){ 
           $this->erro_sql = " Campo Data Opera��o nao Informado.";
           $this->erro_campo = "j18_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j18_rterri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_rterri"])){ 
       $sql  .= $virgula." j18_rterri = $this->j18_rterri ";
       $virgula = ",";
       if(trim($this->j18_rterri) == null ){ 
         $this->erro_sql = " Campo Receita Territorial nao Informado.";
         $this->erro_campo = "j18_rterri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_rpredi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_rpredi"])){ 
       $sql  .= $virgula." j18_rpredi = $this->j18_rpredi ";
       $virgula = ",";
       if(trim($this->j18_rpredi) == null ){ 
         $this->erro_sql = " Campo Receita Predial nao Informado.";
         $this->erro_campo = "j18_rpredi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_vencim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_vencim"])){ 
       $sql  .= $virgula." j18_vencim = $this->j18_vencim ";
       $virgula = ",";
       if(trim($this->j18_vencim) == null ){ 
         $this->erro_sql = " Campo Tabela de Vencimento nao Informado.";
         $this->erro_campo = "j18_vencim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_logradauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_logradauto"])){ 
       $sql  .= $virgula." j18_logradauto = '$this->j18_logradauto' ";
       $virgula = ",";
       if(trim($this->j18_logradauto) == null ){ 
         $this->erro_sql = " Campo C�digo do Logradouro Autom�tico nao Informado.";
         $this->erro_campo = "j18_logradauto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_segundavia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_segundavia"])){ 
       $sql  .= $virgula." j18_segundavia = $this->j18_segundavia ";
       $virgula = ",";
       if(trim($this->j18_segundavia) == null ){ 
         $this->erro_sql = " Campo Segunda Via nao Informado.";
         $this->erro_campo = "j18_segundavia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_infla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_infla"])){ 
       $sql  .= $virgula." j18_infla = '$this->j18_infla' ";
       $virgula = ",";
       if(trim($this->j18_infla) == null ){ 
         $this->erro_sql = " Campo codigo do inflator nao Informado.";
         $this->erro_campo = "j18_infla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_utilizasetfisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_utilizasetfisc"])){ 
       $sql  .= $virgula." j18_utilizasetfisc = '$this->j18_utilizasetfisc' ";
       $virgula = ",";
       if(trim($this->j18_utilizasetfisc) == null ){ 
         $this->erro_sql = " Campo Utiliza Setor FIscal nao Informado.";
         $this->erro_campo = "j18_utilizasetfisc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_testadanumero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_testadanumero"])){ 
       $sql  .= $virgula." j18_testadanumero = '$this->j18_testadanumero' ";
       $virgula = ",";
       if(trim($this->j18_testadanumero) == null ){ 
         $this->erro_sql = " Campo Utiliza numero na testada nao Informado.";
         $this->erro_campo = "j18_testadanumero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_excconscalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_excconscalc"])){ 
       $sql  .= $virgula." j18_excconscalc = '$this->j18_excconscalc' ";
       $virgula = ",";
       if(trim($this->j18_excconscalc) == null ){ 
         $this->erro_sql = " Campo Excluir constru��o do C�lculo nao Informado.";
         $this->erro_campo = "j18_excconscalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_textoprom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_textoprom"])){ 
       $sql  .= $virgula." j18_textoprom = '$this->j18_textoprom' ";
       $virgula = ",";
     }
     if(trim($this->j18_calcvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_calcvenc"])){ 
       $sql  .= $virgula." j18_calcvenc = $this->j18_calcvenc ";
       $virgula = ",";
       if(trim($this->j18_calcvenc) == null ){ 
         $this->erro_sql = " Campo Perguntar vencimentos durante calculo nao Informado.";
         $this->erro_campo = "j18_calcvenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_utilizaloc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_utilizaloc"])){ 
       $sql  .= $virgula." j18_utilizaloc = '$this->j18_utilizaloc' ";
       $virgula = ",";
       if(trim($this->j18_utilizaloc) == null ){ 
         $this->erro_sql = " Campo Utilizar informa��es de localiza��o nao Informado.";
         $this->erro_campo = "j18_utilizaloc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_permvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_permvenc"])){ 
       $sql  .= $virgula." j18_permvenc = $this->j18_permvenc ";
       $virgula = ",";
       if(trim($this->j18_permvenc) == null ){ 
         $this->erro_sql = " Campo Permite escolher vencimentos durante calculo nao Informado.";
         $this->erro_campo = "j18_permvenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_utidadosdiver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_utidadosdiver"])){ 
       $sql  .= $virgula." j18_utidadosdiver = '$this->j18_utidadosdiver' ";
       $virgula = ",";
       if(trim($this->j18_utidadosdiver) == null ){ 
         $this->erro_sql = " Campo Utiliza dados diversos no calculo nao Informado.";
         $this->erro_campo = "j18_utidadosdiver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_dadoscertisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_dadoscertisen"])){ 
       $sql  .= $virgula." j18_dadoscertisen = $this->j18_dadoscertisen ";
       $virgula = ",";
       if(trim($this->j18_dadoscertisen) == null ){ 
         $this->erro_sql = " Campo Dados da certid�o de isen��o nao Informado.";
         $this->erro_campo = "j18_dadoscertisen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_formatsetor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_formatsetor"])){ 
       $sql  .= $virgula." j18_formatsetor = $this->j18_formatsetor ";
       $virgula = ",";
       if(trim($this->j18_formatsetor) == null ){ 
         $this->erro_sql = " Campo Permite digitar para setor nao Informado.";
         $this->erro_campo = "j18_formatsetor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_formatquadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_formatquadra"])){ 
       $sql  .= $virgula." j18_formatquadra = $this->j18_formatquadra ";
       $virgula = ",";
       if(trim($this->j18_formatquadra) == null ){ 
         $this->erro_sql = " Campo Permite digitar para quadra nao Informado.";
         $this->erro_campo = "j18_formatquadra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_formatlote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_formatlote"])){ 
       $sql  .= $virgula." j18_formatlote = $this->j18_formatlote ";
       $virgula = ",";
       if(trim($this->j18_formatlote) == null ){ 
         $this->erro_sql = " Campo Permite digitar para o lote nao Informado.";
         $this->erro_campo = "j18_formatlote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_utilpontos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_utilpontos"])){ 
       $sql  .= $virgula." j18_utilpontos = $this->j18_utilpontos ";
       $virgula = ",";
       if(trim($this->j18_utilpontos) == null ){ 
         $this->erro_sql = " Campo Utilizar pontua��o por constru��o nao Informado.";
         $this->erro_campo = "j18_utilpontos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_ordendent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_ordendent"])){ 
       $sql  .= $virgula." j18_ordendent = $this->j18_ordendent ";
       $virgula = ",";
       if(trim($this->j18_ordendent) == null ){ 
         $this->erro_sql = " Campo Ordem no endereco de entrega nao Informado.";
         $this->erro_campo = "j18_ordendent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_iptuhistisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_iptuhistisen"])){ 
       $sql  .= $virgula." j18_iptuhistisen = $this->j18_iptuhistisen ";
       $virgula = ",";
       if(trim($this->j18_iptuhistisen) == null ){ 
         $this->erro_sql = " Campo C�digo do hist�rico de isen��o nao Informado.";
         $this->erro_campo = "j18_iptuhistisen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_db_sysfuncoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_db_sysfuncoes"])){ 
       $sql  .= $virgula." j18_db_sysfuncoes = $this->j18_db_sysfuncoes ";
       $virgula = ",";
       if(trim($this->j18_db_sysfuncoes) == null ){ 
         $this->erro_sql = " Campo C�digo Fun��o nao Informado.";
         $this->erro_campo = "j18_db_sysfuncoes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_tipoisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_tipoisen"])){ 
       $sql  .= $virgula." j18_tipoisen = $this->j18_tipoisen ";
       $virgula = ",";
       if(trim($this->j18_tipoisen) == null ){ 
         $this->erro_sql = " Campo Codigo da isen��o nao Informado.";
         $this->erro_campo = "j18_tipoisen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j18_perccorrepadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_perccorrepadrao"])){ 
        if(trim($this->j18_perccorrepadrao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j18_perccorrepadrao"])){ 
           $this->j18_perccorrepadrao = "0" ; 
        } 
       $sql  .= $virgula." j18_perccorrepadrao = $this->j18_perccorrepadrao ";
       $virgula = ",";
     }
     if(trim($this->j18_templatecertidaoexitencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_templatecertidaoexitencia"])){ 
        if(trim($this->j18_templatecertidaoexitencia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j18_templatecertidaoexitencia"])){ 
           $this->j18_templatecertidaoexitencia = "0" ; 
        } 
       $sql  .= $virgula." j18_templatecertidaoexitencia = $this->j18_templatecertidaoexitencia ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j18_anousu!=null){
       $sql .= " j18_anousu = $this->j18_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j18_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,808,'$this->j18_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_anousu"]) || $this->j18_anousu != "")
           $resac = db_query("insert into db_acount values($acount,153,808,'".AddSlashes(pg_result($resaco,$conresaco,'j18_anousu'))."','$this->j18_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_vlrref"]) || $this->j18_vlrref != "")
           $resac = db_query("insert into db_acount values($acount,153,809,'".AddSlashes(pg_result($resaco,$conresaco,'j18_vlrref'))."','$this->j18_vlrref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_dtoper"]) || $this->j18_dtoper != "")
           $resac = db_query("insert into db_acount values($acount,153,810,'".AddSlashes(pg_result($resaco,$conresaco,'j18_dtoper'))."','$this->j18_dtoper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_rterri"]) || $this->j18_rterri != "")
           $resac = db_query("insert into db_acount values($acount,153,812,'".AddSlashes(pg_result($resaco,$conresaco,'j18_rterri'))."','$this->j18_rterri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_rpredi"]) || $this->j18_rpredi != "")
           $resac = db_query("insert into db_acount values($acount,153,811,'".AddSlashes(pg_result($resaco,$conresaco,'j18_rpredi'))."','$this->j18_rpredi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_vencim"]) || $this->j18_vencim != "")
           $resac = db_query("insert into db_acount values($acount,153,813,'".AddSlashes(pg_result($resaco,$conresaco,'j18_vencim'))."','$this->j18_vencim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_logradauto"]) || $this->j18_logradauto != "")
           $resac = db_query("insert into db_acount values($acount,153,7320,'".AddSlashes(pg_result($resaco,$conresaco,'j18_logradauto'))."','$this->j18_logradauto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_segundavia"]) || $this->j18_segundavia != "")
           $resac = db_query("insert into db_acount values($acount,153,7415,'".AddSlashes(pg_result($resaco,$conresaco,'j18_segundavia'))."','$this->j18_segundavia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_infla"]) || $this->j18_infla != "")
           $resac = db_query("insert into db_acount values($acount,153,7623,'".AddSlashes(pg_result($resaco,$conresaco,'j18_infla'))."','$this->j18_infla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_utilizasetfisc"]) || $this->j18_utilizasetfisc != "")
           $resac = db_query("insert into db_acount values($acount,153,7870,'".AddSlashes(pg_result($resaco,$conresaco,'j18_utilizasetfisc'))."','$this->j18_utilizasetfisc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_testadanumero"]) || $this->j18_testadanumero != "")
           $resac = db_query("insert into db_acount values($acount,153,7932,'".AddSlashes(pg_result($resaco,$conresaco,'j18_testadanumero'))."','$this->j18_testadanumero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_excconscalc"]) || $this->j18_excconscalc != "")
           $resac = db_query("insert into db_acount values($acount,153,7979,'".AddSlashes(pg_result($resaco,$conresaco,'j18_excconscalc'))."','$this->j18_excconscalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_textoprom"]) || $this->j18_textoprom != "")
           $resac = db_query("insert into db_acount values($acount,153,8646,'".AddSlashes(pg_result($resaco,$conresaco,'j18_textoprom'))."','$this->j18_textoprom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_calcvenc"]) || $this->j18_calcvenc != "")
           $resac = db_query("insert into db_acount values($acount,153,8754,'".AddSlashes(pg_result($resaco,$conresaco,'j18_calcvenc'))."','$this->j18_calcvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_utilizaloc"]) || $this->j18_utilizaloc != "")
           $resac = db_query("insert into db_acount values($acount,153,8756,'".AddSlashes(pg_result($resaco,$conresaco,'j18_utilizaloc'))."','$this->j18_utilizaloc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_permvenc"]) || $this->j18_permvenc != "")
           $resac = db_query("insert into db_acount values($acount,153,8810,'".AddSlashes(pg_result($resaco,$conresaco,'j18_permvenc'))."','$this->j18_permvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_utidadosdiver"]) || $this->j18_utidadosdiver != "")
           $resac = db_query("insert into db_acount values($acount,153,8980,'".AddSlashes(pg_result($resaco,$conresaco,'j18_utidadosdiver'))."','$this->j18_utidadosdiver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_dadoscertisen"]) || $this->j18_dadoscertisen != "")
           $resac = db_query("insert into db_acount values($acount,153,9139,'".AddSlashes(pg_result($resaco,$conresaco,'j18_dadoscertisen'))."','$this->j18_dadoscertisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_formatsetor"]) || $this->j18_formatsetor != "")
           $resac = db_query("insert into db_acount values($acount,153,9542,'".AddSlashes(pg_result($resaco,$conresaco,'j18_formatsetor'))."','$this->j18_formatsetor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_formatquadra"]) || $this->j18_formatquadra != "")
           $resac = db_query("insert into db_acount values($acount,153,9543,'".AddSlashes(pg_result($resaco,$conresaco,'j18_formatquadra'))."','$this->j18_formatquadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_formatlote"]) || $this->j18_formatlote != "")
           $resac = db_query("insert into db_acount values($acount,153,9544,'".AddSlashes(pg_result($resaco,$conresaco,'j18_formatlote'))."','$this->j18_formatlote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_utilpontos"]) || $this->j18_utilpontos != "")
           $resac = db_query("insert into db_acount values($acount,153,9762,'".AddSlashes(pg_result($resaco,$conresaco,'j18_utilpontos'))."','$this->j18_utilpontos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_ordendent"]) || $this->j18_ordendent != "")
           $resac = db_query("insert into db_acount values($acount,153,9856,'".AddSlashes(pg_result($resaco,$conresaco,'j18_ordendent'))."','$this->j18_ordendent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_iptuhistisen"]) || $this->j18_iptuhistisen != "")
           $resac = db_query("insert into db_acount values($acount,153,9858,'".AddSlashes(pg_result($resaco,$conresaco,'j18_iptuhistisen'))."','$this->j18_iptuhistisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_db_sysfuncoes"]) || $this->j18_db_sysfuncoes != "")
           $resac = db_query("insert into db_acount values($acount,153,10824,'".AddSlashes(pg_result($resaco,$conresaco,'j18_db_sysfuncoes'))."','$this->j18_db_sysfuncoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_tipoisen"]) || $this->j18_tipoisen != "")
           $resac = db_query("insert into db_acount values($acount,153,10831,'".AddSlashes(pg_result($resaco,$conresaco,'j18_tipoisen'))."','$this->j18_tipoisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_perccorrepadrao"]) || $this->j18_perccorrepadrao != "")
           $resac = db_query("insert into db_acount values($acount,153,11059,'".AddSlashes(pg_result($resaco,$conresaco,'j18_perccorrepadrao'))."','$this->j18_perccorrepadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j18_templatecertidaoexitencia"]) || $this->j18_templatecertidaoexitencia != "")
           $resac = db_query("insert into db_acount values($acount,153,18859,'".AddSlashes(pg_result($resaco,$conresaco,'j18_templatecertidaoexitencia'))."','$this->j18_templatecertidaoexitencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j18_anousu;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j18_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j18_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j18_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j18_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,808,'$j18_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,153,808,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,809,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_vlrref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,810,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,812,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_rterri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,811,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_rpredi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,813,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_vencim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,7320,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_logradauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,7415,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_segundavia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,7623,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_infla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,7870,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_utilizasetfisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,7932,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_testadanumero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,7979,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_excconscalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,8646,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_textoprom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,8754,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_calcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,8756,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_utilizaloc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,8810,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_permvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,8980,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_utidadosdiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,9139,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_dadoscertisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,9542,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_formatsetor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,9543,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_formatquadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,9544,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_formatlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,9762,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_utilpontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,9856,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_ordendent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,9858,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_iptuhistisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,10824,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_db_sysfuncoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,10831,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_tipoisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,11059,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_perccorrepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,153,18859,'','".AddSlashes(pg_result($resaco,$iresaco,'j18_templatecertidaoexitencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cfiptu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j18_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j18_anousu = $j18_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j18_anousu;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j18_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j18_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cfiptu";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j18_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfiptu ";
     $sql .= "      inner join tipoisen  on  tipoisen.j45_tipo = cfiptu.j18_tipoisen";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = cfiptu.j18_infla";
     $sql .= "      inner join db_sysfuncoes  on  db_sysfuncoes.codfuncao = cfiptu.j18_db_sysfuncoes";
     $sql .= "      left  join db_documentotemplate  on  db_documentotemplate.db82_sequencial = cfiptu.j18_templatecertidaoexitencia";
     $sql .= "      left join db_config  on  db_config.codigo = db_documentotemplate.db82_instit";
     $sql .= "      left join db_documentotemplatetipo  on  db_documentotemplatetipo.db80_sequencial = db_documentotemplate.db82_templatetipo";
     $sql2 = "";
     if($dbwhere==""){
       if($j18_anousu!=null ){
         $sql2 .= " where cfiptu.j18_anousu = $j18_anousu "; 
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
   // funcao do sql 
   function sql_query_file ( $j18_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfiptu ";
     $sql2 = "";
     if($dbwhere==""){
       if($j18_anousu!=null ){
         $sql2 .= " where cfiptu.j18_anousu = $j18_anousu "; 
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
   function alterar_cfiptu ($j18_anousu=null) {
    $this->atualizacampos();
    $sql = " update cfiptu set ";
    $virgula = "";
    if(trim($this->j18_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_anousu"])){
      $sql  .= $virgula." j18_anousu = $this->j18_anousu ";
      $virgula = ",";
      if(trim($this->j18_anousu) == null ){
        $this->erro_sql = " Campo Exerc�cio nao Informado.";
        $this->erro_campo = "j18_anousu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_vlrref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_vlrref"])){
      $sql  .= $virgula." j18_vlrref = $this->j18_vlrref ";
      $virgula = ",";
      if(trim($this->j18_vlrref) == null ){
        $this->erro_sql = " Campo Valor refer�ncia nao Informado.";
        $this->erro_campo = "j18_vlrref";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j18_dtoper_dia"] !="") ){
      $sql  .= $virgula." j18_dtoper = '$this->j18_dtoper' ";
      $virgula = ",";
      if(trim($this->j18_dtoper) == null ){
        $this->erro_sql = " Campo Data Opera��o nao Informado.";
        $this->erro_campo = "j18_dtoper_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["j18_dtoper_dia"])){
        $sql  .= $virgula." j18_dtoper = null ";
        $virgula = ",";
        if(trim($this->j18_dtoper) == null ){
          $this->erro_sql = " Campo Data Opera��o nao Informado.";
          $this->erro_campo = "j18_dtoper_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->j18_rterri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_rterri"])){
      $sql  .= $virgula." j18_rterri = $this->j18_rterri ";
      $virgula = ",";
      if(trim($this->j18_rterri) == null ){
        $this->erro_sql = " Campo Receita Territorial nao Informado.";
        $this->erro_campo = "j18_rterri";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_rpredi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_rpredi"])){
      $sql  .= $virgula." j18_rpredi = $this->j18_rpredi ";
      $virgula = ",";
      if(trim($this->j18_rpredi) == null ){
        $this->erro_sql = " Campo Receita Predial nao Informado.";
        $this->erro_campo = "j18_rpredi";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_vencim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_vencim"])){
      $sql  .= $virgula." j18_vencim = $this->j18_vencim ";
      $virgula = ",";
      if(trim($this->j18_vencim) == null ){
        $this->erro_sql = " Campo Tabela de Vencimento nao Informado.";
        $this->erro_campo = "j18_vencim";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_logradauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_logradauto"])){
      $sql  .= $virgula." j18_logradauto = '$this->j18_logradauto' ";
      $virgula = ",";
      if(trim($this->j18_logradauto) == null ){
        $this->erro_sql = " Campo C�digo do Logradouro Autom�tico nao Informado.";
        $this->erro_campo = "j18_logradauto";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_segundavia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_segundavia"])){
      $sql  .= $virgula." j18_segundavia = $this->j18_segundavia ";
      $virgula = ",";
      if(trim($this->j18_segundavia) == null ){
        $this->erro_sql = " Campo Segunda Via nao Informado.";
        $this->erro_campo = "j18_segundavia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_infla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_infla"])){
      $sql  .= $virgula." j18_infla = '$this->j18_infla' ";
      $virgula = ",";
      if(trim($this->j18_infla) == null ){
        $this->erro_sql = " Campo codigo do inflator nao Informado.";
        $this->erro_campo = "j18_infla";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_utilizasetfisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_utilizasetfisc"])){
      $sql  .= $virgula." j18_utilizasetfisc = '$this->j18_utilizasetfisc' ";
      $virgula = ",";
      if(trim($this->j18_utilizasetfisc) == null ){
        $this->erro_sql = " Campo Utiliza Setor FIscal nao Informado.";
        $this->erro_campo = "j18_utilizasetfisc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_testadanumero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_testadanumero"])){
      $sql  .= $virgula." j18_testadanumero = '$this->j18_testadanumero' ";
      $virgula = ",";
      if(trim($this->j18_testadanumero) == null ){
        $this->erro_sql = " Campo Utiliza numero na testada nao Informado.";
        $this->erro_campo = "j18_testadanumero";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_excconscalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_excconscalc"])){
      $sql  .= $virgula." j18_excconscalc = '$this->j18_excconscalc' ";
      $virgula = ",";
      if(trim($this->j18_excconscalc) == null ){
        $this->erro_sql = " Campo Excluir constru��o do C�lculo nao Informado.";
        $this->erro_campo = "j18_excconscalc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_textoprom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_textoprom"])){
      $sql  .= $virgula." j18_textoprom = '$this->j18_textoprom' ";
      $virgula = ",";
    }
    if(trim($this->j18_calcvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_calcvenc"])){
      $sql  .= $virgula." j18_calcvenc = $this->j18_calcvenc ";
      $virgula = ",";
      if(trim($this->j18_calcvenc) == null ){
        $this->erro_sql = " Campo Perguntar vencimentos durante calculo nao Informado.";
        $this->erro_campo = "j18_calcvenc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_utilizaloc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_utilizaloc"])){
      $sql  .= $virgula." j18_utilizaloc = '$this->j18_utilizaloc' ";
      $virgula = ",";
      if(trim($this->j18_utilizaloc) == null ){
        $this->erro_sql = " Campo Utilizar informa��es de localiza��o nao Informado.";
        $this->erro_campo = "j18_utilizaloc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_permvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_permvenc"])){
      $sql  .= $virgula." j18_permvenc = $this->j18_permvenc ";
      $virgula = ",";
      if(trim($this->j18_permvenc) == null ){
        $this->erro_sql = " Campo Permite escolher vencimentos durante calculo nao Informado.";
        $this->erro_campo = "j18_permvenc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_utidadosdiver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_utidadosdiver"])){
      $sql  .= $virgula." j18_utidadosdiver = '$this->j18_utidadosdiver' ";
      $virgula = ",";
      if(trim($this->j18_utidadosdiver) == null ){
        $this->erro_sql = " Campo Utiliza dados diversos no calculo nao Informado.";
        $this->erro_campo = "j18_utidadosdiver";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_dadoscertisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_dadoscertisen"])){
      $sql  .= $virgula." j18_dadoscertisen = $this->j18_dadoscertisen ";
      $virgula = ",";
      if(trim($this->j18_dadoscertisen) == null ){
        $this->erro_sql = " Campo Dados da certid�o de isen��o nao Informado.";
        $this->erro_campo = "j18_dadoscertisen";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_formatsetor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_formatsetor"])){
      $sql  .= $virgula." j18_formatsetor = $this->j18_formatsetor ";
      $virgula = ",";
      if(trim($this->j18_formatsetor) == null ){
        $this->erro_sql = " Campo Permite digitar para setor nao Informado.";
        $this->erro_campo = "j18_formatsetor";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_formatquadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_formatquadra"])){
      $sql  .= $virgula." j18_formatquadra = $this->j18_formatquadra ";
      $virgula = ",";
      if(trim($this->j18_formatquadra) == null ){
        $this->erro_sql = " Campo Permite digitar para quadra nao Informado.";
        $this->erro_campo = "j18_formatquadra";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_formatlote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_formatlote"])){
      $sql  .= $virgula." j18_formatlote = $this->j18_formatlote ";
      $virgula = ",";
      if(trim($this->j18_formatlote) == null ){
        $this->erro_sql = " Campo Permite digitar para o lote nao Informado.";
        $this->erro_campo = "j18_formatlote";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_utilpontos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_utilpontos"])){
      $sql  .= $virgula." j18_utilpontos = $this->j18_utilpontos ";
      $virgula = ",";
      if(trim($this->j18_utilpontos) == null ){
        $this->erro_sql = " Campo Utilizar pontua��o por constru��o nao Informado.";
        $this->erro_campo = "j18_utilpontos";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_ordendent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_ordendent"])){
      $sql  .= $virgula." j18_ordendent = $this->j18_ordendent ";
      $virgula = ",";
      if(trim($this->j18_ordendent) == null ){
        $this->erro_sql = " Campo Ordem no endereco de entrega nao Informado.";
        $this->erro_campo = "j18_ordendent";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_iptuhistisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_iptuhistisen"])){
      $sql  .= $virgula." j18_iptuhistisen = $this->j18_iptuhistisen ";
      $virgula = ",";
      if(trim($this->j18_iptuhistisen) == null ){
        $this->erro_sql = " Campo C�digo do hist�rico de isen��o nao Informado.";
        $this->erro_campo = "j18_iptuhistisen";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_db_sysfuncoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_db_sysfuncoes"])){
      $sql  .= $virgula." j18_db_sysfuncoes = $this->j18_db_sysfuncoes ";
      $virgula = ",";
      if(trim($this->j18_db_sysfuncoes) == null ){
        $this->erro_sql = " Campo C�digo Fun��o nao Informado.";
        $this->erro_campo = "j18_db_sysfuncoes";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_tipoisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_tipoisen"])){
      $sql  .= $virgula." j18_tipoisen = $this->j18_tipoisen ";
      $virgula = ",";
      if(trim($this->j18_tipoisen) == null ){
        $this->erro_sql = " Campo Codigo da isen��o nao Informado.";
        $this->erro_campo = "j18_tipoisen";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->j18_perccorrepadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_perccorrepadrao"])){
      if(trim($this->j18_perccorrepadrao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j18_perccorrepadrao"])){
        $this->j18_perccorrepadrao = "0" ;
      }
      $sql  .= $virgula." j18_perccorrepadrao = $this->j18_perccorrepadrao ";
      $virgula = ",";
    }
    if(trim($this->j18_templatecertidaoexitencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j18_templatecertidaoexitencia"])){
      if(trim($this->j18_templatecertidaoexitencia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j18_templatecertidaoexitencia"])){
        $this->j18_templatecertidaoexitencia = "null" ;
      }
      $sql  .= $virgula." j18_templatecertidaoexitencia = $this->j18_templatecertidaoexitencia ";
      $virgula = ",";
    }
    $sql .= " where ";
    if($j18_anousu!=null){
      $sql .= " j18_anousu = $this->j18_anousu";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->j18_anousu));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,808,'$this->j18_anousu','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_anousu"]) || $this->j18_anousu != "")
        $resac = db_query("insert into db_acount values($acount,153,808,'".AddSlashes(pg_result($resaco,$conresaco,'j18_anousu'))."','$this->j18_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_vlrref"]) || $this->j18_vlrref != "")
        $resac = db_query("insert into db_acount values($acount,153,809,'".AddSlashes(pg_result($resaco,$conresaco,'j18_vlrref'))."','$this->j18_vlrref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_dtoper"]) || $this->j18_dtoper != "")
        $resac = db_query("insert into db_acount values($acount,153,810,'".AddSlashes(pg_result($resaco,$conresaco,'j18_dtoper'))."','$this->j18_dtoper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_rterri"]) || $this->j18_rterri != "")
        $resac = db_query("insert into db_acount values($acount,153,812,'".AddSlashes(pg_result($resaco,$conresaco,'j18_rterri'))."','$this->j18_rterri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_rpredi"]) || $this->j18_rpredi != "")
        $resac = db_query("insert into db_acount values($acount,153,811,'".AddSlashes(pg_result($resaco,$conresaco,'j18_rpredi'))."','$this->j18_rpredi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_vencim"]) || $this->j18_vencim != "")
        $resac = db_query("insert into db_acount values($acount,153,813,'".AddSlashes(pg_result($resaco,$conresaco,'j18_vencim'))."','$this->j18_vencim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_logradauto"]) || $this->j18_logradauto != "")
        $resac = db_query("insert into db_acount values($acount,153,7320,'".AddSlashes(pg_result($resaco,$conresaco,'j18_logradauto'))."','$this->j18_logradauto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_segundavia"]) || $this->j18_segundavia != "")
        $resac = db_query("insert into db_acount values($acount,153,7415,'".AddSlashes(pg_result($resaco,$conresaco,'j18_segundavia'))."','$this->j18_segundavia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_infla"]) || $this->j18_infla != "")
        $resac = db_query("insert into db_acount values($acount,153,7623,'".AddSlashes(pg_result($resaco,$conresaco,'j18_infla'))."','$this->j18_infla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_utilizasetfisc"]) || $this->j18_utilizasetfisc != "")
        $resac = db_query("insert into db_acount values($acount,153,7870,'".AddSlashes(pg_result($resaco,$conresaco,'j18_utilizasetfisc'))."','$this->j18_utilizasetfisc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_testadanumero"]) || $this->j18_testadanumero != "")
        $resac = db_query("insert into db_acount values($acount,153,7932,'".AddSlashes(pg_result($resaco,$conresaco,'j18_testadanumero'))."','$this->j18_testadanumero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_excconscalc"]) || $this->j18_excconscalc != "")
        $resac = db_query("insert into db_acount values($acount,153,7979,'".AddSlashes(pg_result($resaco,$conresaco,'j18_excconscalc'))."','$this->j18_excconscalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_textoprom"]) || $this->j18_textoprom != "")
        $resac = db_query("insert into db_acount values($acount,153,8646,'".AddSlashes(pg_result($resaco,$conresaco,'j18_textoprom'))."','$this->j18_textoprom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_calcvenc"]) || $this->j18_calcvenc != "")
        $resac = db_query("insert into db_acount values($acount,153,8754,'".AddSlashes(pg_result($resaco,$conresaco,'j18_calcvenc'))."','$this->j18_calcvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_utilizaloc"]) || $this->j18_utilizaloc != "")
        $resac = db_query("insert into db_acount values($acount,153,8756,'".AddSlashes(pg_result($resaco,$conresaco,'j18_utilizaloc'))."','$this->j18_utilizaloc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_permvenc"]) || $this->j18_permvenc != "")
        $resac = db_query("insert into db_acount values($acount,153,8810,'".AddSlashes(pg_result($resaco,$conresaco,'j18_permvenc'))."','$this->j18_permvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_utidadosdiver"]) || $this->j18_utidadosdiver != "")
        $resac = db_query("insert into db_acount values($acount,153,8980,'".AddSlashes(pg_result($resaco,$conresaco,'j18_utidadosdiver'))."','$this->j18_utidadosdiver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_dadoscertisen"]) || $this->j18_dadoscertisen != "")
        $resac = db_query("insert into db_acount values($acount,153,9139,'".AddSlashes(pg_result($resaco,$conresaco,'j18_dadoscertisen'))."','$this->j18_dadoscertisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_formatsetor"]) || $this->j18_formatsetor != "")
        $resac = db_query("insert into db_acount values($acount,153,9542,'".AddSlashes(pg_result($resaco,$conresaco,'j18_formatsetor'))."','$this->j18_formatsetor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_formatquadra"]) || $this->j18_formatquadra != "")
        $resac = db_query("insert into db_acount values($acount,153,9543,'".AddSlashes(pg_result($resaco,$conresaco,'j18_formatquadra'))."','$this->j18_formatquadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_formatlote"]) || $this->j18_formatlote != "")
        $resac = db_query("insert into db_acount values($acount,153,9544,'".AddSlashes(pg_result($resaco,$conresaco,'j18_formatlote'))."','$this->j18_formatlote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_utilpontos"]) || $this->j18_utilpontos != "")
        $resac = db_query("insert into db_acount values($acount,153,9762,'".AddSlashes(pg_result($resaco,$conresaco,'j18_utilpontos'))."','$this->j18_utilpontos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_ordendent"]) || $this->j18_ordendent != "")
        $resac = db_query("insert into db_acount values($acount,153,9856,'".AddSlashes(pg_result($resaco,$conresaco,'j18_ordendent'))."','$this->j18_ordendent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_iptuhistisen"]) || $this->j18_iptuhistisen != "")
        $resac = db_query("insert into db_acount values($acount,153,9858,'".AddSlashes(pg_result($resaco,$conresaco,'j18_iptuhistisen'))."','$this->j18_iptuhistisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_db_sysfuncoes"]) || $this->j18_db_sysfuncoes != "")
        $resac = db_query("insert into db_acount values($acount,153,10824,'".AddSlashes(pg_result($resaco,$conresaco,'j18_db_sysfuncoes'))."','$this->j18_db_sysfuncoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_tipoisen"]) || $this->j18_tipoisen != "")
        $resac = db_query("insert into db_acount values($acount,153,10831,'".AddSlashes(pg_result($resaco,$conresaco,'j18_tipoisen'))."','$this->j18_tipoisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_perccorrepadrao"]) || $this->j18_perccorrepadrao != "")
        $resac = db_query("insert into db_acount values($acount,153,11059,'".AddSlashes(pg_result($resaco,$conresaco,'j18_perccorrepadrao'))."','$this->j18_perccorrepadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["j18_templatecertidaoexitencia"]) || $this->j18_templatecertidaoexitencia != "")
        $resac = db_query("insert into db_acount values($acount,153,18859,'".AddSlashes(pg_result($resaco,$conresaco,'j18_templatecertidaoexitencia'))."','$this->j18_templatecertidaoexitencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Parametros nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->j18_anousu;
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Parametros nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->j18_anousu;
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->j18_anousu;
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
   /**
   * M�todo que retorna objeto com os param�tros do cadasdtro imobili�rio
   * @param $iAnousu Ano base para consulta
   */
  function getParametrosCadastroImobiliario($iAnoUsu = null){
  	 
  	$sSql = "select * from cfiptu ";
  	 
  	if ( !empty($iAnoUsu) ) {
  		$sSql .= " where j18_anousu = " . $iAnoUsu;
  	}
  	$rsSql = db_query($sSql);
  	 
  	if  ( $rsSql && pg_num_rows($rsSql) > 0 ) {
  		return db_utils::getCollectionByRecord($rsSql);
  	}
  	return false;
  }
   function incluir_cfiptu ($j18_anousu){
    $this->atualizacampos();
    if($this->j18_vlrref == null ){
      $this->erro_sql = " Campo Valor refer�ncia nao Informado.";
      $this->erro_campo = "j18_vlrref";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_dtoper == null ){
      $this->erro_sql = " Campo Data Opera��o nao Informado.";
      $this->erro_campo = "j18_dtoper_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_rterri == null ){
      $this->erro_sql = " Campo Receita Territorial nao Informado.";
      $this->erro_campo = "j18_rterri";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_rpredi == null ){
      $this->erro_sql = " Campo Receita Predial nao Informado.";
      $this->erro_campo = "j18_rpredi";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_vencim == null ){
      $this->erro_sql = " Campo Tabela de Vencimento nao Informado.";
      $this->erro_campo = "j18_vencim";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_logradauto == null ){
      $this->erro_sql = " Campo C�digo do Logradouro Autom�tico nao Informado.";
      $this->erro_campo = "j18_logradauto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_segundavia == null ){
      $this->erro_sql = " Campo Segunda Via nao Informado.";
      $this->erro_campo = "j18_segundavia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_infla == null ){
      $this->erro_sql = " Campo codigo do inflator nao Informado.";
      $this->erro_campo = "j18_infla";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_utilizasetfisc == null ){
      $this->erro_sql = " Campo Utiliza Setor FIscal nao Informado.";
      $this->erro_campo = "j18_utilizasetfisc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_testadanumero == null ){
      $this->erro_sql = " Campo Utiliza numero na testada nao Informado.";
      $this->erro_campo = "j18_testadanumero";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_excconscalc == null ){
      $this->erro_sql = " Campo Excluir constru��o do C�lculo nao Informado.";
      $this->erro_campo = "j18_excconscalc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_calcvenc == null ){
      $this->erro_sql = " Campo Perguntar vencimentos durante calculo nao Informado.";
      $this->erro_campo = "j18_calcvenc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_utilizaloc == null ){
      $this->erro_sql = " Campo Utilizar informa��es de localiza��o nao Informado.";
      $this->erro_campo = "j18_utilizaloc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_permvenc == null ){
      $this->erro_sql = " Campo Permite escolher vencimentos durante calculo nao Informado.";
      $this->erro_campo = "j18_permvenc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_utidadosdiver == null ){
      $this->erro_sql = " Campo Utiliza dados diversos no calculo nao Informado.";
      $this->erro_campo = "j18_utidadosdiver";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_dadoscertisen == null ){
      $this->erro_sql = " Campo Dados da certid�o de isen��o nao Informado.";
      $this->erro_campo = "j18_dadoscertisen";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_formatsetor == null ){
      $this->erro_sql = " Campo Permite digitar para setor nao Informado.";
      $this->erro_campo = "j18_formatsetor";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_formatquadra == null ){
      $this->erro_sql = " Campo Permite digitar para quadra nao Informado.";
      $this->erro_campo = "j18_formatquadra";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_formatlote == null ){
      $this->erro_sql = " Campo Permite digitar para o lote nao Informado.";
      $this->erro_campo = "j18_formatlote";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_utilpontos == null ){
      $this->erro_sql = " Campo Utilizar pontua��o por constru��o nao Informado.";
      $this->erro_campo = "j18_utilpontos";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_ordendent == null ){
      $this->erro_sql = " Campo Ordem no endereco de entrega nao Informado.";
      $this->erro_campo = "j18_ordendent";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_iptuhistisen == null ){
      $this->erro_sql = " Campo C�digo do hist�rico de isen��o nao Informado.";
      $this->erro_campo = "j18_iptuhistisen";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_db_sysfuncoes == null ){
      $this->erro_sql = " Campo C�digo Fun��o nao Informado.";
      $this->erro_campo = "j18_db_sysfuncoes";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_tipoisen == null ){
      $this->erro_sql = " Campo Codigo da isen��o nao Informado.";
      $this->erro_campo = "j18_tipoisen";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->j18_perccorrepadrao == null ){
      $this->j18_perccorrepadrao = "0";
    }
    if($this->j18_templatecertidaoexitencia == null ){
      $this->j18_templatecertidaoexitencia = "null";
    }
    $this->j18_anousu = $j18_anousu;
    if(($this->j18_anousu == null) || ($this->j18_anousu == "") ){
      $this->erro_sql = " Campo j18_anousu nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into cfiptu(
                                         j18_anousu 
                                        ,j18_vlrref 
                                        ,j18_dtoper 
                                        ,j18_rterri 
                                        ,j18_rpredi 
                                        ,j18_vencim 
                                        ,j18_logradauto 
                                        ,j18_segundavia 
                                        ,j18_infla 
                                        ,j18_utilizasetfisc 
                                        ,j18_testadanumero 
                                        ,j18_excconscalc 
                                        ,j18_textoprom 
                                        ,j18_calcvenc 
                                        ,j18_utilizaloc 
                                        ,j18_permvenc 
                                        ,j18_utidadosdiver 
                                        ,j18_dadoscertisen 
                                        ,j18_formatsetor 
                                        ,j18_formatquadra 
                                        ,j18_formatlote 
                                        ,j18_utilpontos 
                                        ,j18_ordendent 
                                        ,j18_iptuhistisen 
                                        ,j18_db_sysfuncoes 
                                        ,j18_tipoisen 
                                        ,j18_perccorrepadrao 
                                        ,j18_templatecertidaoexitencia 
                         )
                  values (
    $this->j18_anousu
                                 ,$this->j18_vlrref 
                                 ,".($this->j18_dtoper == "null" || $this->j18_dtoper == ""?"null":"'".$this->j18_dtoper."'")." 
                                 ,$this->j18_rterri 
                                 ,$this->j18_rpredi 
                                 ,$this->j18_vencim 
                                 ,'$this->j18_logradauto' 
                                 ,$this->j18_segundavia 
                                 ,'$this->j18_infla' 
                                 ,'$this->j18_utilizasetfisc' 
                                 ,'$this->j18_testadanumero' 
                                 ,'$this->j18_excconscalc' 
                                 ,'$this->j18_textoprom' 
                                 ,$this->j18_calcvenc 
                                 ,'$this->j18_utilizaloc' 
                                 ,$this->j18_permvenc 
                                 ,'$this->j18_utidadosdiver' 
                                 ,$this->j18_dadoscertisen 
                                 ,$this->j18_formatsetor 
                                 ,$this->j18_formatquadra 
                                 ,$this->j18_formatlote 
                                 ,$this->j18_utilpontos 
                                 ,$this->j18_ordendent 
                                 ,$this->j18_iptuhistisen 
                                 ,$this->j18_db_sysfuncoes 
                                 ,$this->j18_tipoisen 
                                 ,$this->j18_perccorrepadrao 
                                 ,$this->j18_templatecertidaoexitencia 
                        )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Parametros ($this->j18_anousu) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Parametros j� Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Parametros ($this->j18_anousu) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->j18_anousu;
    $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->j18_anousu));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,808,'$this->j18_anousu','I')");
      $resac = db_query("insert into db_acount values($acount,153,808,'','".AddSlashes(pg_result($resaco,0,'j18_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,809,'','".AddSlashes(pg_result($resaco,0,'j18_vlrref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,810,'','".AddSlashes(pg_result($resaco,0,'j18_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,812,'','".AddSlashes(pg_result($resaco,0,'j18_rterri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,811,'','".AddSlashes(pg_result($resaco,0,'j18_rpredi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,813,'','".AddSlashes(pg_result($resaco,0,'j18_vencim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,7320,'','".AddSlashes(pg_result($resaco,0,'j18_logradauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,7415,'','".AddSlashes(pg_result($resaco,0,'j18_segundavia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,7623,'','".AddSlashes(pg_result($resaco,0,'j18_infla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,7870,'','".AddSlashes(pg_result($resaco,0,'j18_utilizasetfisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,7932,'','".AddSlashes(pg_result($resaco,0,'j18_testadanumero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,7979,'','".AddSlashes(pg_result($resaco,0,'j18_excconscalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,8646,'','".AddSlashes(pg_result($resaco,0,'j18_textoprom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,8754,'','".AddSlashes(pg_result($resaco,0,'j18_calcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,8756,'','".AddSlashes(pg_result($resaco,0,'j18_utilizaloc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,8810,'','".AddSlashes(pg_result($resaco,0,'j18_permvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,8980,'','".AddSlashes(pg_result($resaco,0,'j18_utidadosdiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,9139,'','".AddSlashes(pg_result($resaco,0,'j18_dadoscertisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,9542,'','".AddSlashes(pg_result($resaco,0,'j18_formatsetor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,9543,'','".AddSlashes(pg_result($resaco,0,'j18_formatquadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,9544,'','".AddSlashes(pg_result($resaco,0,'j18_formatlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,9762,'','".AddSlashes(pg_result($resaco,0,'j18_utilpontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,9856,'','".AddSlashes(pg_result($resaco,0,'j18_ordendent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,9858,'','".AddSlashes(pg_result($resaco,0,'j18_iptuhistisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,10824,'','".AddSlashes(pg_result($resaco,0,'j18_db_sysfuncoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,10831,'','".AddSlashes(pg_result($resaco,0,'j18_tipoisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,11059,'','".AddSlashes(pg_result($resaco,0,'j18_perccorrepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,153,18859,'','".AddSlashes(pg_result($resaco,0,'j18_templatecertidaoexitencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
   function sql_query_param ( $j18_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from cfiptu ";
     $sql .= "      left join tipoisen      on tipoisen.j45_tipo = cfiptu.j18_tipoisen";
     $sql .= "      left join inflan        on inflan.i01_codigo = cfiptu.j18_infla";
     $sql .= "      left join db_sysfuncoes on db_sysfuncoes.codfuncao = cfiptu.j18_db_sysfuncoes";
     $sql2 = "";
     if($dbwhere==""){
       if($j18_anousu!=null ){
         $sql2 .= " where cfiptu.j18_anousu = $j18_anousu "; 
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