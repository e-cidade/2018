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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcprojeto
class cl_orcprojeto { 
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
   var $o39_codproj = 0; 
   var $o39_descr = null; 
   var $o39_codlei = 0; 
   var $o39_tipoproj = 0; 
   var $o39_anousu = 0; 
   var $o39_numero = null; 
   var $o39_data_dia = null; 
   var $o39_data_mes = null; 
   var $o39_data_ano = null; 
   var $o39_data = null; 
   var $o39_lei = null; 
   var $o39_leidata_dia = null; 
   var $o39_leidata_mes = null; 
   var $o39_leidata_ano = null; 
   var $o39_leidata = null; 
   var $o39_texto = null; 
   var $o39_textolivre = null; 
   var $o39_compllei = null; 
   var $o39_usalimite = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o39_codproj = int4 = codigo do projeto 
                 o39_descr = text = descr do projeto 
                 o39_codlei = int4 = codigo da lei 
                 o39_tipoproj = int4 = tipo do projeto 
                 o39_anousu = int4 = exercicio 
                 o39_numero = varchar(25) = Numero do Decreto 
                 o39_data = date = Data da Decreto 
                 o39_lei = varchar(25) = numero da lei 
                 o39_leidata = date = data da lei 
                 o39_texto = text = texto do projeto 
                 o39_textolivre = text = Texto Livre 
                 o39_compllei = text = Compl. Lei 
                 o39_usalimite = bool = Usa limite da Loa 
                 ";
   //funcao construtor da classe 
   function cl_orcprojeto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprojeto"); 
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
       $this->o39_codproj = ($this->o39_codproj == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_codproj"]:$this->o39_codproj);
       $this->o39_descr = ($this->o39_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_descr"]:$this->o39_descr);
       $this->o39_codlei = ($this->o39_codlei == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_codlei"]:$this->o39_codlei);
       $this->o39_tipoproj = ($this->o39_tipoproj == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_tipoproj"]:$this->o39_tipoproj);
       $this->o39_anousu = ($this->o39_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_anousu"]:$this->o39_anousu);
       $this->o39_numero = ($this->o39_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_numero"]:$this->o39_numero);
       if($this->o39_data == ""){
         $this->o39_data_dia = ($this->o39_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_data_dia"]:$this->o39_data_dia);
         $this->o39_data_mes = ($this->o39_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_data_mes"]:$this->o39_data_mes);
         $this->o39_data_ano = ($this->o39_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_data_ano"]:$this->o39_data_ano);
         if($this->o39_data_dia != ""){
            $this->o39_data = $this->o39_data_ano."-".$this->o39_data_mes."-".$this->o39_data_dia;
         }
       }
       $this->o39_lei = ($this->o39_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_lei"]:$this->o39_lei);
       if($this->o39_leidata == ""){
         $this->o39_leidata_dia = ($this->o39_leidata_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_leidata_dia"]:$this->o39_leidata_dia);
         $this->o39_leidata_mes = ($this->o39_leidata_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_leidata_mes"]:$this->o39_leidata_mes);
         $this->o39_leidata_ano = ($this->o39_leidata_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_leidata_ano"]:$this->o39_leidata_ano);
         if($this->o39_leidata_dia != ""){
            $this->o39_leidata = $this->o39_leidata_ano."-".$this->o39_leidata_mes."-".$this->o39_leidata_dia;
         }
       }
       $this->o39_texto = ($this->o39_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_texto"]:$this->o39_texto);
       $this->o39_textolivre = ($this->o39_textolivre == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_textolivre"]:$this->o39_textolivre);
       $this->o39_compllei = ($this->o39_compllei == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_compllei"]:$this->o39_compllei);
       $this->o39_usalimite = ($this->o39_usalimite == "f"?@$GLOBALS["HTTP_POST_VARS"]["o39_usalimite"]:$this->o39_usalimite);
     }else{
       $this->o39_codproj = ($this->o39_codproj == ""?@$GLOBALS["HTTP_POST_VARS"]["o39_codproj"]:$this->o39_codproj);
     }
   }
   // funcao para inclusao
   function incluir ($o39_codproj){ 
      $this->atualizacampos();
     if($this->o39_descr == null ){ 
       $this->erro_sql = " Campo descr do projeto nao Informado.";
       $this->erro_campo = "o39_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o39_codlei == null ){ 
       $this->erro_sql = " Campo codigo da lei nao Informado.";
       $this->erro_campo = "o39_codlei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o39_tipoproj == null ){ 
       $this->erro_sql = " Campo tipo do projeto nao Informado.";
       $this->erro_campo = "o39_tipoproj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o39_anousu == null ){ 
       $this->erro_sql = " Campo exercicio nao Informado.";
       $this->erro_campo = "o39_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o39_data == null ){ 
       $this->o39_data = "null";
     }
     if($this->o39_leidata == null ){ 
       $this->o39_leidata = "null";
     }
     if($this->o39_usalimite == null ){ 
       $this->o39_usalimite = "false";
     }
     if($o39_codproj == "" || $o39_codproj == null ){
       $result = db_query("select nextval('orcprojeto_o39_codproj_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcprojeto_o39_codproj_seq do campo: o39_codproj"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o39_codproj = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcprojeto_o39_codproj_seq");
       if(($result != false) && (pg_result($result,0,0) < $o39_codproj)){
         $this->erro_sql = " Campo o39_codproj maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o39_codproj = $o39_codproj; 
       }
     }
     if(($this->o39_codproj == null) || ($this->o39_codproj == "") ){ 
       $this->erro_sql = " Campo o39_codproj nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprojeto(
                                       o39_codproj 
                                      ,o39_descr 
                                      ,o39_codlei 
                                      ,o39_tipoproj 
                                      ,o39_anousu 
                                      ,o39_numero 
                                      ,o39_data 
                                      ,o39_lei 
                                      ,o39_leidata 
                                      ,o39_texto 
                                      ,o39_textolivre 
                                      ,o39_compllei 
                                      ,o39_usalimite 
                       )
                values (
                                $this->o39_codproj 
                               ,'$this->o39_descr' 
                               ,$this->o39_codlei 
                               ,$this->o39_tipoproj 
                               ,$this->o39_anousu 
                               ,'$this->o39_numero' 
                               ,".($this->o39_data == "null" || $this->o39_data == ""?"null":"'".$this->o39_data."'")." 
                               ,'$this->o39_lei' 
                               ,".($this->o39_leidata == "null" || $this->o39_leidata == ""?"null":"'".$this->o39_leidata."'")." 
                               ,'$this->o39_texto' 
                               ,'$this->o39_textolivre' 
                               ,'$this->o39_compllei' 
                               ,'$this->o39_usalimite' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->o39_codproj) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->o39_codproj) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o39_codproj;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o39_codproj));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6037,'$this->o39_codproj','I')");
       $resac = db_query("insert into db_acount values($acount,969,6037,'','".AddSlashes(pg_result($resaco,0,'o39_codproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6038,'','".AddSlashes(pg_result($resaco,0,'o39_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6039,'','".AddSlashes(pg_result($resaco,0,'o39_codlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6040,'','".AddSlashes(pg_result($resaco,0,'o39_tipoproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6101,'','".AddSlashes(pg_result($resaco,0,'o39_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6358,'','".AddSlashes(pg_result($resaco,0,'o39_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6359,'','".AddSlashes(pg_result($resaco,0,'o39_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6559,'','".AddSlashes(pg_result($resaco,0,'o39_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6560,'','".AddSlashes(pg_result($resaco,0,'o39_leidata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6659,'','".AddSlashes(pg_result($resaco,0,'o39_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,6730,'','".AddSlashes(pg_result($resaco,0,'o39_textolivre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,9965,'','".AddSlashes(pg_result($resaco,0,'o39_compllei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,969,17714,'','".AddSlashes(pg_result($resaco,0,'o39_usalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o39_codproj=null) { 
      $this->atualizacampos();
     $sql = " update orcprojeto set ";
     $virgula = "";
     if(trim($this->o39_codproj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_codproj"])){ 
       $sql  .= $virgula." o39_codproj = $this->o39_codproj ";
       $virgula = ",";
       if(trim($this->o39_codproj) == null ){ 
         $this->erro_sql = " Campo codigo do projeto nao Informado.";
         $this->erro_campo = "o39_codproj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o39_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_descr"])){ 
       $sql  .= $virgula." o39_descr = '$this->o39_descr' ";
       $virgula = ",";
       if(trim($this->o39_descr) == null ){ 
         $this->erro_sql = " Campo descr do projeto nao Informado.";
         $this->erro_campo = "o39_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o39_codlei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_codlei"])){ 
       $sql  .= $virgula." o39_codlei = $this->o39_codlei ";
       $virgula = ",";
       if(trim($this->o39_codlei) == null ){ 
         $this->erro_sql = " Campo codigo da lei nao Informado.";
         $this->erro_campo = "o39_codlei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o39_tipoproj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_tipoproj"])){ 
       $sql  .= $virgula." o39_tipoproj = $this->o39_tipoproj ";
       $virgula = ",";
       if(trim($this->o39_tipoproj) == null ){ 
         $this->erro_sql = " Campo tipo do projeto nao Informado.";
         $this->erro_campo = "o39_tipoproj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o39_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_anousu"])){ 
       $sql  .= $virgula." o39_anousu = $this->o39_anousu ";
       $virgula = ",";
       if(trim($this->o39_anousu) == null ){ 
         $this->erro_sql = " Campo exercicio nao Informado.";
         $this->erro_campo = "o39_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o39_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_numero"])){ 
       $sql  .= $virgula." o39_numero = '$this->o39_numero' ";
       $virgula = ",";
     }
     if(trim($this->o39_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o39_data_dia"] !="") ){ 
       $sql  .= $virgula." o39_data = '$this->o39_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o39_data_dia"])){ 
         $sql  .= $virgula." o39_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o39_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_lei"])){ 
       $sql  .= $virgula." o39_lei = '$this->o39_lei' ";
       $virgula = ",";
     }
     if(trim($this->o39_leidata)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_leidata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o39_leidata_dia"] !="") ){ 
       $sql  .= $virgula." o39_leidata = '$this->o39_leidata' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o39_leidata_dia"])){ 
         $sql  .= $virgula." o39_leidata = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o39_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_texto"])){ 
       $sql  .= $virgula." o39_texto = '$this->o39_texto' ";
       $virgula = ",";
     }
     if(trim($this->o39_textolivre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_textolivre"])){ 
       $sql  .= $virgula." o39_textolivre = '$this->o39_textolivre' ";
       $virgula = ",";
     }
     if(trim($this->o39_compllei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_compllei"])){ 
       $sql  .= $virgula." o39_compllei = '$this->o39_compllei' ";
       $virgula = ",";
     }
     if(trim($this->o39_usalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o39_usalimite"])){ 
       $sql  .= $virgula." o39_usalimite = '$this->o39_usalimite' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o39_codproj!=null){
       $sql .= " o39_codproj = $this->o39_codproj";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o39_codproj));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6037,'$this->o39_codproj','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_codproj"]) || $this->o39_codproj != "")
           $resac = db_query("insert into db_acount values($acount,969,6037,'".AddSlashes(pg_result($resaco,$conresaco,'o39_codproj'))."','$this->o39_codproj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_descr"]) || $this->o39_descr != "")
           $resac = db_query("insert into db_acount values($acount,969,6038,'".AddSlashes(pg_result($resaco,$conresaco,'o39_descr'))."','$this->o39_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_codlei"]) || $this->o39_codlei != "")
           $resac = db_query("insert into db_acount values($acount,969,6039,'".AddSlashes(pg_result($resaco,$conresaco,'o39_codlei'))."','$this->o39_codlei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_tipoproj"]) || $this->o39_tipoproj != "")
           $resac = db_query("insert into db_acount values($acount,969,6040,'".AddSlashes(pg_result($resaco,$conresaco,'o39_tipoproj'))."','$this->o39_tipoproj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_anousu"]) || $this->o39_anousu != "")
           $resac = db_query("insert into db_acount values($acount,969,6101,'".AddSlashes(pg_result($resaco,$conresaco,'o39_anousu'))."','$this->o39_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_numero"]) || $this->o39_numero != "")
           $resac = db_query("insert into db_acount values($acount,969,6358,'".AddSlashes(pg_result($resaco,$conresaco,'o39_numero'))."','$this->o39_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_data"]) || $this->o39_data != "")
           $resac = db_query("insert into db_acount values($acount,969,6359,'".AddSlashes(pg_result($resaco,$conresaco,'o39_data'))."','$this->o39_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_lei"]) || $this->o39_lei != "")
           $resac = db_query("insert into db_acount values($acount,969,6559,'".AddSlashes(pg_result($resaco,$conresaco,'o39_lei'))."','$this->o39_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_leidata"]) || $this->o39_leidata != "")
           $resac = db_query("insert into db_acount values($acount,969,6560,'".AddSlashes(pg_result($resaco,$conresaco,'o39_leidata'))."','$this->o39_leidata',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_texto"]) || $this->o39_texto != "")
           $resac = db_query("insert into db_acount values($acount,969,6659,'".AddSlashes(pg_result($resaco,$conresaco,'o39_texto'))."','$this->o39_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_textolivre"]) || $this->o39_textolivre != "")
           $resac = db_query("insert into db_acount values($acount,969,6730,'".AddSlashes(pg_result($resaco,$conresaco,'o39_textolivre'))."','$this->o39_textolivre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_compllei"]) || $this->o39_compllei != "")
           $resac = db_query("insert into db_acount values($acount,969,9965,'".AddSlashes(pg_result($resaco,$conresaco,'o39_compllei'))."','$this->o39_compllei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o39_usalimite"]) || $this->o39_usalimite != "")
           $resac = db_query("insert into db_acount values($acount,969,17714,'".AddSlashes(pg_result($resaco,$conresaco,'o39_usalimite'))."','$this->o39_usalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o39_codproj;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o39_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o39_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o39_codproj=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o39_codproj));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6037,'$o39_codproj','E')");
         $resac = db_query("insert into db_acount values($acount,969,6037,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_codproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6038,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6039,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_codlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6040,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_tipoproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6101,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6358,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6359,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6559,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6560,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_leidata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6659,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,6730,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_textolivre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,9965,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_compllei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,969,17714,'','".AddSlashes(pg_result($resaco,$iresaco,'o39_usalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprojeto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o39_codproj != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o39_codproj = $o39_codproj ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o39_codproj;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o39_codproj;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o39_codproj;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprojeto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query($o39_codproj = null, $campos = "*", $ordem = null, $dbwhere = "") {
                $sql = "select ";
                if ($campos != "*") {
                        $campos_sql = split("#", $campos);
                        $virgula = "";
                        for ($i = 0; $i < sizeof($campos_sql); $i ++) {
                                $sql .= $virgula.$campos_sql[$i];
                                $virgula = ",";
                        }
                } else {
                        $sql .= $campos;
                }
                $sql .= " from orcprojeto ";
                $sql .= "      inner join orclei  on  orclei.o45_codlei = orcprojeto.o39_codlei";
                $sql .= "      left outer join orcsuplem on o46_codlei = orcprojeto.o39_codproj ";
                $sql .= "      left outer join orcsuplemretif retif on retif.o48_retificado = orcprojeto.o39_codproj ";
                $sql .= "      left outer join orcsuplemretif proj on proj.o48_projeto = orcprojeto.o39_codproj ";
                $sql2 = "";
                if ($dbwhere == "") {
                        if ($o39_codproj != null) {
                                $sql2 .= " where orcprojeto.o39_codproj = $o39_codproj ";
                        }
                } else
                        if ($dbwhere != "") {
                                $sql2 = " where $dbwhere";
                        }
                $sql .= $sql2;
                if ($ordem != null) {
                        $sql .= " order by ";
                        $campos_sql = split("#", $ordem);
                        $virgula = "";
                        for ($i = 0; $i < sizeof($campos_sql); $i ++) {
                                $sql .= $virgula.$campos_sql[$i];
                                $virgula = ",";
                          }
                }
                return $sql;
        }
   function sql_query_file($o39_codproj = null, $campos = "*", $ordem = null, $dbwhere = "") {
                $sql = "select ";
                if ($campos != "*") {
                        $campos_sql = split("#", $campos);
                        $virgula = "";
                        for ($i = 0; $i < sizeof($campos_sql); $i ++) {
                                $sql .= $virgula.$campos_sql[$i];
                                $virgula = ",";
                        }
                } else {
                        $sql .= $campos;
                }
                $sql .= " from orcprojeto ";

                $sql2 = "";
                if ($dbwhere == "") {
                        if ($o39_codproj != null) {
                                $sql2 .= " where orcprojeto.o39_codproj = $o39_codproj ";
                        }
                } else
                        if ($dbwhere != "") {
                                $sql2 = " where $dbwhere";
                        }
                $sql .= $sql2;
                if ($ordem != null) {
                        $sql .= " order by ";
                        $campos_sql = split("#", $ordem);
                        $virgula = "";
                        for ($i = 0; $i < sizeof($campos_sql); $i ++) {
                                  $sql .= $virgula.$campos_sql[$i];
                                $virgula = ",";
                        }
                }
                return $sql;
        }
   function sql_query_proj($o39_codproj = null, $campos = "*", $ordem = null, $dbwhere = "") {
                $sql = "select ";
                if ($campos != "*") {
                        $campos_sql = split("#", $campos);
                        $virgula = "";
                        for ($i = 0; $i < sizeof($campos_sql); $i ++) {
                                $sql .= $virgula.$campos_sql[$i];
                                $virgula = ",";
                        }
                } else {
                        $sql .= $campos;
                }
                $sql .= " from orcprojeto ";
                $sql .= "      inner join orclei  on  orclei.o45_codlei = orcprojeto.o39_codlei ";
                $sql .= "      left join orcprojlan on o51_codproj = o39_codproj ";
                $sql2 = "";
                if ($dbwhere == "") {
                        if ($o39_codproj != null) {
                                $sql2 .= " where orcprojeto.o39_codproj = $o39_codproj ";
                        }
                } else
                        if ($dbwhere != "") {
                                $sql2 = " where $dbwhere";
                        }
                $sql .= $sql2;
                if ($ordem != null) {
                        $sql .= " order by ";
                        $campos_sql = split("#", $ordem);
                          $virgula = "";
                        for ($i = 0; $i < sizeof($campos_sql); $i ++) {
                                $sql .= $virgula.$campos_sql[$i];
                                $virgula = ",";
                        }
                }
                return $sql;
        }
   public function sql_query_projeto($o39_codproj = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
              $sql .= $virgula.$campos_sql[$i];
              $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from orcprojeto ";
    $sql .= "      inner join orclei    on  orclei.o45_codlei        = orcprojeto.o39_codlei ";
    $sql .= "      left join orcprojlan on o51_codproj               = o39_codproj ";
    $sql .= "      left join orcprojetoorcprojetolei on o39_codproj  = o139_orcprojeto ";
    $sql .= "      left join orcprojetolei on o139_orcprojetolei     = o138_sequencial ";
    $sql2 = "";
    if ($dbwhere == "") {
            if ($o39_codproj != null) {
                    $sql2 .= " where orcprojeto.o39_codproj = $o39_codproj ";
            }
    } else
            if ($dbwhere != "") {
                    $sql2 = " where $dbwhere";
            }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>