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
//CLASSE DA ENTIDADE rhestagiocomissao
class cl_rhestagiocomissao { 
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
   var $h59_sequencial = 0; 
   var $h59_dtini_dia = null; 
   var $h59_dtini_mes = null; 
   var $h59_dtini_ano = null; 
   var $h59_dtini = null; 
   var $h59_dtfim_dia = null; 
   var $h59_dtfim_mes = null; 
   var $h59_dtfim_ano = null; 
   var $h59_dtfim = null; 
   var $h59_descr = null; 
   var $h59_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h59_sequencial = int4 = Cód. Sequencial 
                 h59_dtini = date = Data inicial 
                 h59_dtfim = date = Data final 
                 h59_descr = varchar(40) = Descrição: 
                 h59_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_rhestagiocomissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagiocomissao"); 
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
       $this->h59_sequencial = ($this->h59_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_sequencial"]:$this->h59_sequencial);
       if($this->h59_dtini == ""){
         $this->h59_dtini_dia = ($this->h59_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_dtini_dia"]:$this->h59_dtini_dia);
         $this->h59_dtini_mes = ($this->h59_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_dtini_mes"]:$this->h59_dtini_mes);
         $this->h59_dtini_ano = ($this->h59_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_dtini_ano"]:$this->h59_dtini_ano);
         if($this->h59_dtini_dia != ""){
            $this->h59_dtini = $this->h59_dtini_ano."-".$this->h59_dtini_mes."-".$this->h59_dtini_dia;
         }
       }
       if($this->h59_dtfim == ""){
         $this->h59_dtfim_dia = ($this->h59_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_dtfim_dia"]:$this->h59_dtfim_dia);
         $this->h59_dtfim_mes = ($this->h59_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_dtfim_mes"]:$this->h59_dtfim_mes);
         $this->h59_dtfim_ano = ($this->h59_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_dtfim_ano"]:$this->h59_dtfim_ano);
         if($this->h59_dtfim_dia != ""){
            $this->h59_dtfim = $this->h59_dtfim_ano."-".$this->h59_dtfim_mes."-".$this->h59_dtfim_dia;
         }
       }
       $this->h59_descr = ($this->h59_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_descr"]:$this->h59_descr);
       $this->h59_instit = ($this->h59_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_instit"]:$this->h59_instit);
     }else{
       $this->h59_sequencial = ($this->h59_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h59_sequencial"]:$this->h59_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h59_sequencial){ 
      $this->atualizacampos();
     if($this->h59_dtini == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "h59_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h59_dtfim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "h59_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h59_descr == null ){ 
       $this->erro_sql = " Campo Descrição: nao Informado.";
       $this->erro_campo = "h59_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h59_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "h59_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h59_sequencial == "" || $h59_sequencial == null ){
       $result = db_query("select nextval('rhestagiocomissao_h59_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagiocomissao_h59_sequencial_seq do campo: h59_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h59_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagiocomissao_h59_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h59_sequencial)){
         $this->erro_sql = " Campo h59_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h59_sequencial = $h59_sequencial; 
       }
     }
     if(($this->h59_sequencial == null) || ($this->h59_sequencial == "") ){ 
       $this->erro_sql = " Campo h59_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagiocomissao(
                                       h59_sequencial 
                                      ,h59_dtini 
                                      ,h59_dtfim 
                                      ,h59_descr 
                                      ,h59_instit 
                       )
                values (
                                $this->h59_sequencial 
                               ,".($this->h59_dtini == "null" || $this->h59_dtini == ""?"null":"'".$this->h59_dtini."'")." 
                               ,".($this->h59_dtfim == "null" || $this->h59_dtfim == ""?"null":"'".$this->h59_dtfim."'")." 
                               ,'$this->h59_descr' 
                               ,$this->h59_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Comissão de estágios ($this->h59_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Comissão de estágios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Comissão de estágios ($this->h59_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h59_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h59_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10874,'$this->h59_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1874,10874,'','".AddSlashes(pg_result($resaco,0,'h59_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1874,10875,'','".AddSlashes(pg_result($resaco,0,'h59_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1874,10876,'','".AddSlashes(pg_result($resaco,0,'h59_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1874,10934,'','".AddSlashes(pg_result($resaco,0,'h59_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1874,10948,'','".AddSlashes(pg_result($resaco,0,'h59_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h59_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagiocomissao set ";
     $virgula = "";
     if(trim($this->h59_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h59_sequencial"])){ 
       $sql  .= $virgula." h59_sequencial = $this->h59_sequencial ";
       $virgula = ",";
       if(trim($this->h59_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "h59_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h59_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h59_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h59_dtini_dia"] !="") ){ 
       $sql  .= $virgula." h59_dtini = '$this->h59_dtini' ";
       $virgula = ",";
       if(trim($this->h59_dtini) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "h59_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h59_dtini_dia"])){ 
         $sql  .= $virgula." h59_dtini = null ";
         $virgula = ",";
         if(trim($this->h59_dtini) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "h59_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h59_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h59_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h59_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." h59_dtfim = '$this->h59_dtfim' ";
       $virgula = ",";
       if(trim($this->h59_dtfim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "h59_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h59_dtfim_dia"])){ 
         $sql  .= $virgula." h59_dtfim = null ";
         $virgula = ",";
         if(trim($this->h59_dtfim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "h59_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h59_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h59_descr"])){ 
       $sql  .= $virgula." h59_descr = '$this->h59_descr' ";
       $virgula = ",";
       if(trim($this->h59_descr) == null ){ 
         $this->erro_sql = " Campo Descrição: nao Informado.";
         $this->erro_campo = "h59_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h59_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h59_instit"])){ 
       $sql  .= $virgula." h59_instit = $this->h59_instit ";
       $virgula = ",";
       if(trim($this->h59_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "h59_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h59_sequencial!=null){
       $sql .= " h59_sequencial = $this->h59_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h59_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10874,'$this->h59_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h59_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1874,10874,'".AddSlashes(pg_result($resaco,$conresaco,'h59_sequencial'))."','$this->h59_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h59_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1874,10875,'".AddSlashes(pg_result($resaco,$conresaco,'h59_dtini'))."','$this->h59_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h59_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1874,10876,'".AddSlashes(pg_result($resaco,$conresaco,'h59_dtfim'))."','$this->h59_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h59_descr"]))
           $resac = db_query("insert into db_acount values($acount,1874,10934,'".AddSlashes(pg_result($resaco,$conresaco,'h59_descr'))."','$this->h59_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h59_instit"]))
           $resac = db_query("insert into db_acount values($acount,1874,10948,'".AddSlashes(pg_result($resaco,$conresaco,'h59_instit'))."','$this->h59_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Comissão de estágios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h59_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Comissão de estágios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h59_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h59_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h59_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h59_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10874,'$h59_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1874,10874,'','".AddSlashes(pg_result($resaco,$iresaco,'h59_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1874,10875,'','".AddSlashes(pg_result($resaco,$iresaco,'h59_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1874,10876,'','".AddSlashes(pg_result($resaco,$iresaco,'h59_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1874,10934,'','".AddSlashes(pg_result($resaco,$iresaco,'h59_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1874,10948,'','".AddSlashes(pg_result($resaco,$iresaco,'h59_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagiocomissao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h59_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h59_sequencial = $h59_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Comissão de estágios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h59_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Comissão de estágios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h59_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h59_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagiocomissao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h59_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagiocomissao ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhestagiocomissao.h59_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($h59_sequencial!=null ){
         $sql2 .= " where rhestagiocomissao.h59_sequencial = $h59_sequencial "; 
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
   function sql_query_file ( $h59_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagiocomissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($h59_sequencial!=null ){
         $sql2 .= " where rhestagiocomissao.h59_sequencial = $h59_sequencial "; 
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