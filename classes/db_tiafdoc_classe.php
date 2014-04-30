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

//MODULO: fiscal
//CLASSE DA ENTIDADE tiafdoc
class cl_tiafdoc { 
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
   var $y99_coddoc = 0; 
   var $y99_codtiaf = 0; 
   var $y99_tiafdoc = 0; 
   var $y99_dtini_dia = null; 
   var $y99_dtini_mes = null; 
   var $y99_dtini_ano = null; 
   var $y99_dtini = null; 
   var $y99_dtfim_dia = null; 
   var $y99_dtfim_mes = null; 
   var $y99_dtfim_ano = null; 
   var $y99_dtfim = null; 
   var $y99_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y99_coddoc = int4 = Codigo do documento 
                 y99_codtiaf = int4 = Código Tiaf 
                 y99_tiafdoc = int4 = Codigo do documento 
                 y99_dtini = date = data inicio 
                 y99_dtfim = date = data final 
                 y99_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_tiafdoc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tiafdoc"); 
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
       $this->y99_coddoc = ($this->y99_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_coddoc"]:$this->y99_coddoc);
       $this->y99_codtiaf = ($this->y99_codtiaf == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_codtiaf"]:$this->y99_codtiaf);
       $this->y99_tiafdoc = ($this->y99_tiafdoc == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_tiafdoc"]:$this->y99_tiafdoc);
       if($this->y99_dtini == ""){
         $this->y99_dtini_dia = ($this->y99_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_dtini_dia"]:$this->y99_dtini_dia);
         $this->y99_dtini_mes = ($this->y99_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_dtini_mes"]:$this->y99_dtini_mes);
         $this->y99_dtini_ano = ($this->y99_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_dtini_ano"]:$this->y99_dtini_ano);
         if($this->y99_dtini_dia != ""){
            $this->y99_dtini = $this->y99_dtini_ano."-".$this->y99_dtini_mes."-".$this->y99_dtini_dia;
         }
       }
       if($this->y99_dtfim == ""){
         $this->y99_dtfim_dia = ($this->y99_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_dtfim_dia"]:$this->y99_dtfim_dia);
         $this->y99_dtfim_mes = ($this->y99_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_dtfim_mes"]:$this->y99_dtfim_mes);
         $this->y99_dtfim_ano = ($this->y99_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_dtfim_ano"]:$this->y99_dtfim_ano);
         if($this->y99_dtfim_dia != ""){
            $this->y99_dtfim = $this->y99_dtfim_ano."-".$this->y99_dtfim_mes."-".$this->y99_dtfim_dia;
         }
       }
       $this->y99_obs = ($this->y99_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_obs"]:$this->y99_obs);
     }else{
       $this->y99_coddoc = ($this->y99_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["y99_coddoc"]:$this->y99_coddoc);
     }
   }
   // funcao para inclusao
   function incluir ($y99_coddoc){ 
      $this->atualizacampos();
     if($this->y99_codtiaf == null ){ 
       $this->erro_sql = " Campo Código Tiaf nao Informado.";
       $this->erro_campo = "y99_codtiaf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y99_tiafdoc == null ){ 
       $this->erro_sql = " Campo Codigo do documento nao Informado.";
       $this->erro_campo = "y99_tiafdoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y99_dtini == null ){ 
       $this->erro_sql = " Campo data inicio nao Informado.";
       $this->erro_campo = "y99_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y99_dtfim == null ){ 
       $this->erro_sql = " Campo data final nao Informado.";
       $this->erro_campo = "y99_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y99_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "y99_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y99_coddoc == "" || $y99_coddoc == null ){
       $result = db_query("select nextval('tiafdoc_y99_coddoc_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tiafdoc_y99_coddoc_seq do campo: y99_coddoc"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y99_coddoc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tiafdoc_y99_coddoc_seq");
       if(($result != false) && (pg_result($result,0,0) < $y99_coddoc)){
         $this->erro_sql = " Campo y99_coddoc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y99_coddoc = $y99_coddoc; 
       }
     }
     if(($this->y99_coddoc == null) || ($this->y99_coddoc == "") ){ 
       $this->erro_sql = " Campo y99_coddoc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tiafdoc(
                                       y99_coddoc 
                                      ,y99_codtiaf 
                                      ,y99_tiafdoc 
                                      ,y99_dtini 
                                      ,y99_dtfim 
                                      ,y99_obs 
                       )
                values (
                                $this->y99_coddoc 
                               ,$this->y99_codtiaf 
                               ,$this->y99_tiafdoc 
                               ,".($this->y99_dtini == "null" || $this->y99_dtini == ""?"null":"'".$this->y99_dtini."'")." 
                               ,".($this->y99_dtfim == "null" || $this->y99_dtfim == ""?"null":"'".$this->y99_dtfim."'")." 
                               ,'$this->y99_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documentos do tiaf ($this->y99_coddoc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documentos do tiaf já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documentos do tiaf ($this->y99_coddoc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y99_coddoc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y99_coddoc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7360,'$this->y99_coddoc','I')");
       $resac = db_query("insert into db_acount values($acount,1228,7360,'','".AddSlashes(pg_result($resaco,0,'y99_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1228,7361,'','".AddSlashes(pg_result($resaco,0,'y99_codtiaf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1228,7362,'','".AddSlashes(pg_result($resaco,0,'y99_tiafdoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1228,7364,'','".AddSlashes(pg_result($resaco,0,'y99_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1228,7365,'','".AddSlashes(pg_result($resaco,0,'y99_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1228,7371,'','".AddSlashes(pg_result($resaco,0,'y99_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y99_coddoc=null) { 
      $this->atualizacampos();
     $sql = " update tiafdoc set ";
     $virgula = "";
     if(trim($this->y99_coddoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y99_coddoc"])){ 
       $sql  .= $virgula." y99_coddoc = $this->y99_coddoc ";
       $virgula = ",";
       if(trim($this->y99_coddoc) == null ){ 
         $this->erro_sql = " Campo Codigo do documento nao Informado.";
         $this->erro_campo = "y99_coddoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y99_codtiaf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y99_codtiaf"])){ 
       $sql  .= $virgula." y99_codtiaf = $this->y99_codtiaf ";
       $virgula = ",";
       if(trim($this->y99_codtiaf) == null ){ 
         $this->erro_sql = " Campo Código Tiaf nao Informado.";
         $this->erro_campo = "y99_codtiaf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y99_tiafdoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y99_tiafdoc"])){ 
       $sql  .= $virgula." y99_tiafdoc = $this->y99_tiafdoc ";
       $virgula = ",";
       if(trim($this->y99_tiafdoc) == null ){ 
         $this->erro_sql = " Campo Codigo do documento nao Informado.";
         $this->erro_campo = "y99_tiafdoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y99_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y99_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y99_dtini_dia"] !="") ){ 
       $sql  .= $virgula." y99_dtini = '$this->y99_dtini' ";
       $virgula = ",";
       if(trim($this->y99_dtini) == null ){ 
         $this->erro_sql = " Campo data inicio nao Informado.";
         $this->erro_campo = "y99_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y99_dtini_dia"])){ 
         $sql  .= $virgula." y99_dtini = null ";
         $virgula = ",";
         if(trim($this->y99_dtini) == null ){ 
           $this->erro_sql = " Campo data inicio nao Informado.";
           $this->erro_campo = "y99_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y99_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y99_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y99_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." y99_dtfim = '$this->y99_dtfim' ";
       $virgula = ",";
       if(trim($this->y99_dtfim) == null ){ 
         $this->erro_sql = " Campo data final nao Informado.";
         $this->erro_campo = "y99_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y99_dtfim_dia"])){ 
         $sql  .= $virgula." y99_dtfim = null ";
         $virgula = ",";
         if(trim($this->y99_dtfim) == null ){ 
           $this->erro_sql = " Campo data final nao Informado.";
           $this->erro_campo = "y99_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y99_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y99_obs"])){ 
       $sql  .= $virgula." y99_obs = '$this->y99_obs' ";
       $virgula = ",";
       if(trim($this->y99_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "y99_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y99_coddoc!=null){
       $sql .= " y99_coddoc = $this->y99_coddoc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y99_coddoc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7360,'$this->y99_coddoc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y99_coddoc"]))
           $resac = db_query("insert into db_acount values($acount,1228,7360,'".AddSlashes(pg_result($resaco,$conresaco,'y99_coddoc'))."','$this->y99_coddoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y99_codtiaf"]))
           $resac = db_query("insert into db_acount values($acount,1228,7361,'".AddSlashes(pg_result($resaco,$conresaco,'y99_codtiaf'))."','$this->y99_codtiaf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y99_tiafdoc"]))
           $resac = db_query("insert into db_acount values($acount,1228,7362,'".AddSlashes(pg_result($resaco,$conresaco,'y99_tiafdoc'))."','$this->y99_tiafdoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y99_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1228,7364,'".AddSlashes(pg_result($resaco,$conresaco,'y99_dtini'))."','$this->y99_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y99_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1228,7365,'".AddSlashes(pg_result($resaco,$conresaco,'y99_dtfim'))."','$this->y99_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y99_obs"]))
           $resac = db_query("insert into db_acount values($acount,1228,7371,'".AddSlashes(pg_result($resaco,$conresaco,'y99_obs'))."','$this->y99_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos do tiaf nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y99_coddoc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documentos do tiaf nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y99_coddoc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y99_coddoc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y99_coddoc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y99_coddoc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7360,'$y99_coddoc','E')");
         $resac = db_query("insert into db_acount values($acount,1228,7360,'','".AddSlashes(pg_result($resaco,$iresaco,'y99_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1228,7361,'','".AddSlashes(pg_result($resaco,$iresaco,'y99_codtiaf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1228,7362,'','".AddSlashes(pg_result($resaco,$iresaco,'y99_tiafdoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1228,7364,'','".AddSlashes(pg_result($resaco,$iresaco,'y99_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1228,7365,'','".AddSlashes(pg_result($resaco,$iresaco,'y99_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1228,7371,'','".AddSlashes(pg_result($resaco,$iresaco,'y99_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tiafdoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y99_coddoc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y99_coddoc = $y99_coddoc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos do tiaf nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y99_coddoc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documentos do tiaf nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y99_coddoc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y99_coddoc;
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
        $this->erro_sql   = "Record Vazio na Tabela:tiafdoc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y99_coddoc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiafdoc ";
     $sql .= "      inner join tiaf  on  tiaf.y90_codtiaf = tiafdoc.y99_codtiaf";
     $sql .= "      inner join tiaftipodoc  on  tiaftipodoc.y98_tiafdoc = tiafdoc.y99_tiafdoc";
     $sql2 = "";
     if($dbwhere==""){
       if($y99_coddoc!=null ){
         $sql2 .= " where tiafdoc.y99_coddoc = $y99_coddoc "; 
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
   function sql_query_file ( $y99_coddoc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiafdoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($y99_coddoc!=null ){
         $sql2 .= " where tiafdoc.y99_coddoc = $y99_coddoc "; 
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