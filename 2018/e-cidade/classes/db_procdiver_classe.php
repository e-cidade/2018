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

//MODULO: diversos
//CLASSE DA ENTIDADE procdiver
class cl_procdiver { 
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
   var $dv09_procdiver = 0; 
   var $dv09_descra = null; 
   var $dv09_descr = null; 
   var $dv09_receit = 0; 
   var $dv09_hist = 0; 
   var $dv09_proced = 0; 
   var $dv09_tipo = 0; 
   var $dv09_instit = 0; 
   var $dv09_dtlimite_dia = null; 
   var $dv09_dtlimite_mes = null; 
   var $dv09_dtlimite_ano = null; 
   var $dv09_dtlimite = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 dv09_procdiver = int4 = Procedência 
                 dv09_descra = varchar(20) = Descrição Abreviada 
                 dv09_descr = varchar(40) = Descrição 
                 dv09_receit = int4 = Receita 
                 dv09_hist = int4 = Histórico do Cálculo 
                 dv09_proced = int4 = Procedência da dívida 
                 dv09_tipo = int4 = Tipo de Débito 
                 dv09_instit = int4 = Instituição 
                 dv09_dtlimite = date = Data limite 
                 ";
   //funcao construtor da classe 
   function cl_procdiver() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procdiver"); 
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
       $this->dv09_procdiver = ($this->dv09_procdiver == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_procdiver"]:$this->dv09_procdiver);
       $this->dv09_descra = ($this->dv09_descra == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_descra"]:$this->dv09_descra);
       $this->dv09_descr = ($this->dv09_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_descr"]:$this->dv09_descr);
       $this->dv09_receit = ($this->dv09_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_receit"]:$this->dv09_receit);
       $this->dv09_hist = ($this->dv09_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_hist"]:$this->dv09_hist);
       $this->dv09_proced = ($this->dv09_proced == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_proced"]:$this->dv09_proced);
       $this->dv09_tipo = ($this->dv09_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_tipo"]:$this->dv09_tipo);
       $this->dv09_instit = ($this->dv09_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_instit"]:$this->dv09_instit);
       if($this->dv09_dtlimite == ""){
         $this->dv09_dtlimite_dia = ($this->dv09_dtlimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_dtlimite_dia"]:$this->dv09_dtlimite_dia);
         $this->dv09_dtlimite_mes = ($this->dv09_dtlimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_dtlimite_mes"]:$this->dv09_dtlimite_mes);
         $this->dv09_dtlimite_ano = ($this->dv09_dtlimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_dtlimite_ano"]:$this->dv09_dtlimite_ano);
         if($this->dv09_dtlimite_dia != ""){
            $this->dv09_dtlimite = $this->dv09_dtlimite_ano."-".$this->dv09_dtlimite_mes."-".$this->dv09_dtlimite_dia;
         }
       }
     }else{
       $this->dv09_procdiver = ($this->dv09_procdiver == ""?@$GLOBALS["HTTP_POST_VARS"]["dv09_procdiver"]:$this->dv09_procdiver);
     }
   }
   // funcao para inclusao
   function incluir ($dv09_procdiver){ 
      $this->atualizacampos();
     if($this->dv09_descra == null ){ 
       $this->erro_sql = " Campo Descrição Abreviada nao Informado.";
       $this->erro_campo = "dv09_descra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv09_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "dv09_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv09_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "dv09_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv09_hist == null ){ 
       $this->erro_sql = " Campo Histórico do Cálculo nao Informado.";
       $this->erro_campo = "dv09_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv09_proced == null ){ 
       $this->erro_sql = " Campo Procedência da dívida nao Informado.";
       $this->erro_campo = "dv09_proced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv09_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Débito nao Informado.";
       $this->erro_campo = "dv09_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv09_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "dv09_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv09_dtlimite == null ){ 
       $this->dv09_dtlimite = "null";
     }
     if($dv09_procdiver == "" || $dv09_procdiver == null ){
       $result = db_query("select nextval('procdiver_dv09_procdiver_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procdiver_dv09_procdiver_seq do campo: dv09_procdiver"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->dv09_procdiver = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procdiver_dv09_procdiver_seq");
       if(($result != false) && (pg_result($result,0,0) < $dv09_procdiver)){
         $this->erro_sql = " Campo dv09_procdiver maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->dv09_procdiver = $dv09_procdiver; 
       }
     }
     if(($this->dv09_procdiver == null) || ($this->dv09_procdiver == "") ){ 
       $this->erro_sql = " Campo dv09_procdiver nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procdiver(
                                       dv09_procdiver 
                                      ,dv09_descra 
                                      ,dv09_descr 
                                      ,dv09_receit 
                                      ,dv09_hist 
                                      ,dv09_proced 
                                      ,dv09_tipo 
                                      ,dv09_instit 
                                      ,dv09_dtlimite 
                       )
                values (
                                $this->dv09_procdiver 
                               ,'$this->dv09_descra' 
                               ,'$this->dv09_descr' 
                               ,$this->dv09_receit 
                               ,$this->dv09_hist 
                               ,$this->dv09_proced 
                               ,$this->dv09_tipo 
                               ,$this->dv09_instit 
                               ,".($this->dv09_dtlimite == "null" || $this->dv09_dtlimite == ""?"null":"'".$this->dv09_dtlimite."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "procedências dos diversos ($this->dv09_procdiver) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "procedências dos diversos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "procedências dos diversos ($this->dv09_procdiver) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv09_procdiver;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->dv09_procdiver));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3508,'$this->dv09_procdiver','I')");
       $resac = db_query("insert into db_acount values($acount,374,3508,'','".AddSlashes(pg_result($resaco,0,'dv09_procdiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,374,3509,'','".AddSlashes(pg_result($resaco,0,'dv09_descra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,374,3510,'','".AddSlashes(pg_result($resaco,0,'dv09_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,374,3511,'','".AddSlashes(pg_result($resaco,0,'dv09_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,374,3512,'','".AddSlashes(pg_result($resaco,0,'dv09_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,374,3513,'','".AddSlashes(pg_result($resaco,0,'dv09_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,374,4764,'','".AddSlashes(pg_result($resaco,0,'dv09_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,374,10553,'','".AddSlashes(pg_result($resaco,0,'dv09_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,374,18595,'','".AddSlashes(pg_result($resaco,0,'dv09_dtlimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($dv09_procdiver=null) { 
      $this->atualizacampos();
     $sql = " update procdiver set ";
     $virgula = "";
     if(trim($this->dv09_procdiver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_procdiver"])){ 
       $sql  .= $virgula." dv09_procdiver = $this->dv09_procdiver ";
       $virgula = ",";
       if(trim($this->dv09_procdiver) == null ){ 
         $this->erro_sql = " Campo Procedência nao Informado.";
         $this->erro_campo = "dv09_procdiver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv09_descra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_descra"])){ 
       $sql  .= $virgula." dv09_descra = '$this->dv09_descra' ";
       $virgula = ",";
       if(trim($this->dv09_descra) == null ){ 
         $this->erro_sql = " Campo Descrição Abreviada nao Informado.";
         $this->erro_campo = "dv09_descra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv09_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_descr"])){ 
       $sql  .= $virgula." dv09_descr = '$this->dv09_descr' ";
       $virgula = ",";
       if(trim($this->dv09_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "dv09_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv09_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_receit"])){ 
       $sql  .= $virgula." dv09_receit = $this->dv09_receit ";
       $virgula = ",";
       if(trim($this->dv09_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "dv09_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv09_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_hist"])){ 
       $sql  .= $virgula." dv09_hist = $this->dv09_hist ";
       $virgula = ",";
       if(trim($this->dv09_hist) == null ){ 
         $this->erro_sql = " Campo Histórico do Cálculo nao Informado.";
         $this->erro_campo = "dv09_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv09_proced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_proced"])){ 
       $sql  .= $virgula." dv09_proced = $this->dv09_proced ";
       $virgula = ",";
       if(trim($this->dv09_proced) == null ){ 
         $this->erro_sql = " Campo Procedência da dívida nao Informado.";
         $this->erro_campo = "dv09_proced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv09_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_tipo"])){ 
       $sql  .= $virgula." dv09_tipo = $this->dv09_tipo ";
       $virgula = ",";
       if(trim($this->dv09_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Débito nao Informado.";
         $this->erro_campo = "dv09_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv09_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_instit"])){ 
       $sql  .= $virgula." dv09_instit = $this->dv09_instit ";
       $virgula = ",";
       if(trim($this->dv09_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "dv09_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv09_dtlimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv09_dtlimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dv09_dtlimite_dia"] !="") ){ 
       $sql  .= $virgula." dv09_dtlimite = '$this->dv09_dtlimite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_dtlimite_dia"])){ 
         $sql  .= $virgula." dv09_dtlimite = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($dv09_procdiver!=null){
       $sql .= " dv09_procdiver = $this->dv09_procdiver";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->dv09_procdiver));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3508,'$this->dv09_procdiver','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_procdiver"]) || $this->dv09_procdiver != "")
           $resac = db_query("insert into db_acount values($acount,374,3508,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_procdiver'))."','$this->dv09_procdiver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_descra"]) || $this->dv09_descra != "")
           $resac = db_query("insert into db_acount values($acount,374,3509,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_descra'))."','$this->dv09_descra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_descr"]) || $this->dv09_descr != "")
           $resac = db_query("insert into db_acount values($acount,374,3510,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_descr'))."','$this->dv09_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_receit"]) || $this->dv09_receit != "")
           $resac = db_query("insert into db_acount values($acount,374,3511,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_receit'))."','$this->dv09_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_hist"]) || $this->dv09_hist != "")
           $resac = db_query("insert into db_acount values($acount,374,3512,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_hist'))."','$this->dv09_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_proced"]) || $this->dv09_proced != "")
           $resac = db_query("insert into db_acount values($acount,374,3513,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_proced'))."','$this->dv09_proced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_tipo"]) || $this->dv09_tipo != "")
           $resac = db_query("insert into db_acount values($acount,374,4764,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_tipo'))."','$this->dv09_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_instit"]) || $this->dv09_instit != "")
           $resac = db_query("insert into db_acount values($acount,374,10553,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_instit'))."','$this->dv09_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv09_dtlimite"]) || $this->dv09_dtlimite != "")
           $resac = db_query("insert into db_acount values($acount,374,18595,'".AddSlashes(pg_result($resaco,$conresaco,'dv09_dtlimite'))."','$this->dv09_dtlimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     
     if( $result == false ){
     	 
       $this->erro_banco  = str_replace("\n","",@pg_last_error());
       $this->erro_sql    = "procedências dos diversos nao Alterado. Alteracao Abortada.\\n";
       $this->erro_sql   .= "Valores : ".$this->dv09_procdiver;
       $this->erro_msg     = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   		 .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status 		= "0";
       $this->numrows_alterar = 0;
       
       return false;
       
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "procedências dos diversos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv09_procdiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv09_procdiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($dv09_procdiver=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($dv09_procdiver));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3508,'$dv09_procdiver','E')");
         $resac = db_query("insert into db_acount values($acount,374,3508,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_procdiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,374,3509,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_descra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,374,3510,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,374,3511,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,374,3512,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,374,3513,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,374,4764,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,374,10553,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,374,18595,'','".AddSlashes(pg_result($resaco,$iresaco,'dv09_dtlimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procdiver
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($dv09_procdiver != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " dv09_procdiver = $dv09_procdiver ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "procedências dos diversos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$dv09_procdiver;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "procedências dos diversos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$dv09_procdiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$dv09_procdiver;
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
        $this->erro_sql   = "Record Vazio na Tabela:procdiver";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $dv09_procdiver=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procdiver ";
     $sql .= "      inner join histcalc        on histcalc.k01_codigo = procdiver.dv09_hist";
     $sql .= "      inner join tabrec          on tabrec.k02_codigo   = procdiver.dv09_receit";
     $sql .= "      inner join arretipo        on arretipo.k00_tipo   = procdiver.dv09_tipo";
     $sql .= "      inner join db_config       on db_config.codigo    = procdiver.dv09_instit";
     $sql .= "      inner join proced          on proced.v03_codigo   = procdiver.dv09_proced";
     $sql .= "      inner join tabrecjm        on tabrecjm.k02_codjm  = tabrec.k02_codjm";
     $sql .= "      inner join db_config as a  on a.codigo            = arretipo.k00_instit";
     $sql .= "      inner join cadtipo         on cadtipo.k03_tipo    = arretipo.k03_tipo";
     $sql .= "      inner join cgm             on cgm.z01_numcgm      = db_config.numcgm";
     $sql .= "      inner join histcalc as c   on c.k01_codigo        = proced.k00_hist";
     $sql .= "      inner join tabrec  as b    on b.k02_codigo        = proced.v03_receit";
     $sql2 = "";
     if($dbwhere==""){
       if($dv09_procdiver!=null ){
         $sql2 .= " where procdiver.dv09_procdiver = $dv09_procdiver "; 
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
   function sql_query_file ( $dv09_procdiver=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procdiver ";
     $sql2 = "";
     if($dbwhere==""){
       if($dv09_procdiver!=null ){
         $sql2 .= " where procdiver.dv09_procdiver = $dv09_procdiver "; 
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