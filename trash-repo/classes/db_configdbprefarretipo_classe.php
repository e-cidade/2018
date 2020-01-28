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

//MODULO: prefeitura
//CLASSE DA ENTIDADE configdbprefarretipo
class cl_configdbprefarretipo { 
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
   var $w17_sequencial = 0; 
   var $w17_arretipo = 0; 
   var $w17_instit = 0; 
   var $w17_dtini_dia = null; 
   var $w17_dtini_mes = null; 
   var $w17_dtini_ano = null; 
   var $w17_dtini = null; 
   var $w17_dtfim_dia = null; 
   var $w17_dtfim_mes = null; 
   var $w17_dtfim_ano = null; 
   var $w17_dtfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w17_sequencial = int4 = Sequêncial 
                 w17_arretipo = int4 = Tipo de Débito 
                 w17_instit = int4 = Intituíção 
                 w17_dtini = date = Vencimento Inicial 
                 w17_dtfim = date = Vencimento Final 
                 ";
   //funcao construtor da classe 
   function cl_configdbprefarretipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("configdbprefarretipo"); 
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
       $this->w17_sequencial = ($this->w17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_sequencial"]:$this->w17_sequencial);
       $this->w17_arretipo = ($this->w17_arretipo == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_arretipo"]:$this->w17_arretipo);
       $this->w17_instit = ($this->w17_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_instit"]:$this->w17_instit);
       if($this->w17_dtini == ""){
         $this->w17_dtini_dia = ($this->w17_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_dtini_dia"]:$this->w17_dtini_dia);
         $this->w17_dtini_mes = ($this->w17_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_dtini_mes"]:$this->w17_dtini_mes);
         $this->w17_dtini_ano = ($this->w17_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_dtini_ano"]:$this->w17_dtini_ano);
         if($this->w17_dtini_dia != ""){
            $this->w17_dtini = $this->w17_dtini_ano."-".$this->w17_dtini_mes."-".$this->w17_dtini_dia;
         }
       }
       if($this->w17_dtfim == ""){
         $this->w17_dtfim_dia = ($this->w17_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_dtfim_dia"]:$this->w17_dtfim_dia);
         $this->w17_dtfim_mes = ($this->w17_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_dtfim_mes"]:$this->w17_dtfim_mes);
         $this->w17_dtfim_ano = ($this->w17_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_dtfim_ano"]:$this->w17_dtfim_ano);
         if($this->w17_dtfim_dia != ""){
            $this->w17_dtfim = $this->w17_dtfim_ano."-".$this->w17_dtfim_mes."-".$this->w17_dtfim_dia;
         }
       }
     }else{
       $this->w17_sequencial = ($this->w17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w17_sequencial"]:$this->w17_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($w17_sequencial){ 
      $this->atualizacampos();
     if($this->w17_arretipo == null ){ 
       $this->erro_sql = " Campo Tipo de Débito nao Informado.";
       $this->erro_campo = "w17_arretipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w17_instit == null ){ 
       $this->erro_sql = " Campo Intituíção nao Informado.";
       $this->erro_campo = "w17_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w17_dtini == null ){ 
       $this->erro_sql = " Campo Vencimento Inicial nao Informado.";
       $this->erro_campo = "w17_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w17_dtfim == null ){ 
       $this->erro_sql = " Campo Vencimento Final nao Informado.";
       $this->erro_campo = "w17_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($w17_sequencial == "" || $w17_sequencial == null ){
       $result = db_query("select nextval('configdbprefarretipo_w17_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: configdbprefarretipo_w17_sequencial_seq do campo: w17_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->w17_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from configdbprefarretipo_w17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $w17_sequencial)){
         $this->erro_sql = " Campo w17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->w17_sequencial = $w17_sequencial; 
       }
     }
     if(($this->w17_sequencial == null) || ($this->w17_sequencial == "") ){ 
       $this->erro_sql = " Campo w17_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into configdbprefarretipo(
                                       w17_sequencial 
                                      ,w17_arretipo 
                                      ,w17_instit 
                                      ,w17_dtini 
                                      ,w17_dtfim 
                       )
                values (
                                $this->w17_sequencial 
                               ,$this->w17_arretipo 
                               ,$this->w17_instit 
                               ,".($this->w17_dtini == "null" || $this->w17_dtini == ""?"null":"'".$this->w17_dtini."'")." 
                               ,".($this->w17_dtfim == "null" || $this->w17_dtfim == ""?"null":"'".$this->w17_dtfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Conf dos Tipos de Débitos ($this->w17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Conf dos Tipos de Débitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Conf dos Tipos de Débitos ($this->w17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w17_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14619,'$this->w17_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2570,14619,'','".AddSlashes(pg_result($resaco,0,'w17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2570,14620,'','".AddSlashes(pg_result($resaco,0,'w17_arretipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2570,14621,'','".AddSlashes(pg_result($resaco,0,'w17_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2570,14622,'','".AddSlashes(pg_result($resaco,0,'w17_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2570,14623,'','".AddSlashes(pg_result($resaco,0,'w17_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w17_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update configdbprefarretipo set ";
     $virgula = "";
     if(trim($this->w17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w17_sequencial"])){ 
       $sql  .= $virgula." w17_sequencial = $this->w17_sequencial ";
       $virgula = ",";
       if(trim($this->w17_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequêncial nao Informado.";
         $this->erro_campo = "w17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w17_arretipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w17_arretipo"])){ 
       $sql  .= $virgula." w17_arretipo = $this->w17_arretipo ";
       $virgula = ",";
       if(trim($this->w17_arretipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Débito nao Informado.";
         $this->erro_campo = "w17_arretipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w17_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w17_instit"])){ 
       $sql  .= $virgula." w17_instit = $this->w17_instit ";
       $virgula = ",";
       if(trim($this->w17_instit) == null ){ 
         $this->erro_sql = " Campo Intituíção nao Informado.";
         $this->erro_campo = "w17_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w17_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w17_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w17_dtini_dia"] !="") ){ 
       $sql  .= $virgula." w17_dtini = '$this->w17_dtini' ";
       $virgula = ",";
       if(trim($this->w17_dtini) == null ){ 
         $this->erro_sql = " Campo Vencimento Inicial nao Informado.";
         $this->erro_campo = "w17_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w17_dtini_dia"])){ 
         $sql  .= $virgula." w17_dtini = null ";
         $virgula = ",";
         if(trim($this->w17_dtini) == null ){ 
           $this->erro_sql = " Campo Vencimento Inicial nao Informado.";
           $this->erro_campo = "w17_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->w17_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w17_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w17_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." w17_dtfim = '$this->w17_dtfim' ";
       $virgula = ",";
       if(trim($this->w17_dtfim) == null ){ 
         $this->erro_sql = " Campo Vencimento Final nao Informado.";
         $this->erro_campo = "w17_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w17_dtfim_dia"])){ 
         $sql  .= $virgula." w17_dtfim = null ";
         $virgula = ",";
         if(trim($this->w17_dtfim) == null ){ 
           $this->erro_sql = " Campo Vencimento Final nao Informado.";
           $this->erro_campo = "w17_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($w17_sequencial!=null){
       $sql .= " w17_sequencial = $this->w17_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w17_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14619,'$this->w17_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w17_sequencial"]) || $this->w17_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2570,14619,'".AddSlashes(pg_result($resaco,$conresaco,'w17_sequencial'))."','$this->w17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w17_arretipo"]) || $this->w17_arretipo != "")
           $resac = db_query("insert into db_acount values($acount,2570,14620,'".AddSlashes(pg_result($resaco,$conresaco,'w17_arretipo'))."','$this->w17_arretipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w17_instit"]) || $this->w17_instit != "")
           $resac = db_query("insert into db_acount values($acount,2570,14621,'".AddSlashes(pg_result($resaco,$conresaco,'w17_instit'))."','$this->w17_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w17_dtini"]) || $this->w17_dtini != "")
           $resac = db_query("insert into db_acount values($acount,2570,14622,'".AddSlashes(pg_result($resaco,$conresaco,'w17_dtini'))."','$this->w17_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w17_dtfim"]) || $this->w17_dtfim != "")
           $resac = db_query("insert into db_acount values($acount,2570,14623,'".AddSlashes(pg_result($resaco,$conresaco,'w17_dtfim'))."','$this->w17_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conf dos Tipos de Débitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conf dos Tipos de Débitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w17_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w17_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14619,'$w17_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2570,14619,'','".AddSlashes(pg_result($resaco,$iresaco,'w17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2570,14620,'','".AddSlashes(pg_result($resaco,$iresaco,'w17_arretipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2570,14621,'','".AddSlashes(pg_result($resaco,$iresaco,'w17_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2570,14622,'','".AddSlashes(pg_result($resaco,$iresaco,'w17_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2570,14623,'','".AddSlashes(pg_result($resaco,$iresaco,'w17_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from configdbprefarretipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w17_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w17_sequencial = $w17_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conf dos Tipos de Débitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conf dos Tipos de Débitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:configdbprefarretipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $w17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from configdbprefarretipo ";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = configdbprefarretipo.w17_arretipo";
     $sql .= "      inner join db_config  on  db_config.codigo = configdbprefarretipo.w17_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($w17_sequencial!=null ){
         $sql2 .= " where configdbprefarretipo.w17_sequencial = $w17_sequencial "; 
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
   function sql_query_file ( $w17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from configdbprefarretipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($w17_sequencial!=null ){
         $sql2 .= " where configdbprefarretipo.w17_sequencial = $w17_sequencial "; 
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