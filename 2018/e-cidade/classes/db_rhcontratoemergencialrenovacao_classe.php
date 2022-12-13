<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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
//MODULO: pessoal
//CLASSE DA ENTIDADE rhcontratoemergencialrenovacao
class cl_rhcontratoemergencialrenovacao { 
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
   var $rh164_sequencial = 0; 
   var $rh164_contratoemergencial = 0; 
   var $rh164_descricao = null; 
   var $rh164_datainicio_dia = null; 
   var $rh164_datainicio_mes = null; 
   var $rh164_datainicio_ano = null; 
   var $rh164_datainicio = null; 
   var $rh164_datafim_dia = null; 
   var $rh164_datafim_mes = null; 
   var $rh164_datafim_ano = null; 
   var $rh164_datafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh164_sequencial = int4 = Código 
                 rh164_contratoemergencial = int4 = Contrato Emergencial 
                 rh164_descricao = varchar(255) = Descrição 
                 rh164_datainicio = date = Data início 
                 rh164_datafim = date = Data término 
                 ";
   //funcao construtor da classe 
   function cl_rhcontratoemergencialrenovacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhcontratoemergencialrenovacao"); 
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
       $this->rh164_sequencial = ($this->rh164_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_sequencial"]:$this->rh164_sequencial);
       $this->rh164_contratoemergencial = ($this->rh164_contratoemergencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_contratoemergencial"]:$this->rh164_contratoemergencial);
       $this->rh164_descricao = ($this->rh164_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_descricao"]:$this->rh164_descricao);
       if($this->rh164_datainicio == ""){
         $this->rh164_datainicio_dia = ($this->rh164_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_datainicio_dia"]:$this->rh164_datainicio_dia);
         $this->rh164_datainicio_mes = ($this->rh164_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_datainicio_mes"]:$this->rh164_datainicio_mes);
         $this->rh164_datainicio_ano = ($this->rh164_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_datainicio_ano"]:$this->rh164_datainicio_ano);
         if($this->rh164_datainicio_dia != ""){
            $this->rh164_datainicio = $this->rh164_datainicio_ano."-".$this->rh164_datainicio_mes."-".$this->rh164_datainicio_dia;
         }
       }
       if($this->rh164_datafim == ""){
         $this->rh164_datafim_dia = ($this->rh164_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_datafim_dia"]:$this->rh164_datafim_dia);
         $this->rh164_datafim_mes = ($this->rh164_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_datafim_mes"]:$this->rh164_datafim_mes);
         $this->rh164_datafim_ano = ($this->rh164_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_datafim_ano"]:$this->rh164_datafim_ano);
         if($this->rh164_datafim_dia != ""){
            $this->rh164_datafim = $this->rh164_datafim_ano."-".$this->rh164_datafim_mes."-".$this->rh164_datafim_dia;
         }
       }
     }else{
       $this->rh164_sequencial = ($this->rh164_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh164_sequencial"]:$this->rh164_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh164_sequencial){ 
      $this->atualizacampos();
     if($this->rh164_contratoemergencial == null ){ 
       $this->erro_sql = " Campo Contrato Emergencial não informado.";
       $this->erro_campo = "rh164_contratoemergencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh164_datainicio == null ){ 
       $this->erro_sql = " Campo Data início não informado.";
       $this->erro_campo = "rh164_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh164_datafim == null ){ 
       $this->erro_sql = " Campo Data término não informado.";
       $this->erro_campo = "rh164_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh164_sequencial == "" || $rh164_sequencial == null ){
       $result = db_query("select nextval('rhcontratoemergencialrenovacao_rh164_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhcontratoemergencialrenovacao_rh164_sequencial_seq do campo: rh164_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh164_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhcontratoemergencialrenovacao_rh164_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh164_sequencial)){
         $this->erro_sql = " Campo rh164_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh164_sequencial = $rh164_sequencial; 
       }
     }
     if(($this->rh164_sequencial == null) || ($this->rh164_sequencial == "") ){ 
       $this->erro_sql = " Campo rh164_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhcontratoemergencialrenovacao(
                                       rh164_sequencial 
                                      ,rh164_contratoemergencial 
                                      ,rh164_descricao 
                                      ,rh164_datainicio 
                                      ,rh164_datafim 
                       )
                values (
                                $this->rh164_sequencial 
                               ,$this->rh164_contratoemergencial 
                               ,'$this->rh164_descricao' 
                               ,".($this->rh164_datainicio == "null" || $this->rh164_datainicio == ""?"null":"'".$this->rh164_datainicio."'")." 
                               ,".($this->rh164_datafim == "null" || $this->rh164_datafim == ""?"null":"'".$this->rh164_datafim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Renovações Contrato Emergencial ($this->rh164_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Renovações Contrato Emergencial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Renovações Contrato Emergencial ($this->rh164_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh164_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh164_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21196,'$this->rh164_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3817,21196,'','".AddSlashes(pg_result($resaco,0,'rh164_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3817,21197,'','".AddSlashes(pg_result($resaco,0,'rh164_contratoemergencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3817,21198,'','".AddSlashes(pg_result($resaco,0,'rh164_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3817,21199,'','".AddSlashes(pg_result($resaco,0,'rh164_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3817,21200,'','".AddSlashes(pg_result($resaco,0,'rh164_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh164_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhcontratoemergencialrenovacao set ";
     $virgula = "";
     if(trim($this->rh164_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh164_sequencial"])){ 
       $sql  .= $virgula." rh164_sequencial = $this->rh164_sequencial ";
       $virgula = ",";
       if(trim($this->rh164_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh164_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh164_contratoemergencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh164_contratoemergencial"])){ 
       $sql  .= $virgula." rh164_contratoemergencial = $this->rh164_contratoemergencial ";
       $virgula = ",";
       if(trim($this->rh164_contratoemergencial) == null ){ 
         $this->erro_sql = " Campo Contrato Emergencial não informado.";
         $this->erro_campo = "rh164_contratoemergencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh164_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh164_descricao"])){ 
       $sql  .= $virgula." rh164_descricao = '$this->rh164_descricao' ";
       $virgula = ",";
     }
     if(trim($this->rh164_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh164_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh164_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." rh164_datainicio = '$this->rh164_datainicio' ";
       $virgula = ",";
       if(trim($this->rh164_datainicio) == null ){ 
         $this->erro_sql = " Campo Data início não informado.";
         $this->erro_campo = "rh164_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh164_datainicio_dia"])){ 
         $sql  .= $virgula." rh164_datainicio = null ";
         $virgula = ",";
         if(trim($this->rh164_datainicio) == null ){ 
           $this->erro_sql = " Campo Data início não informado.";
           $this->erro_campo = "rh164_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh164_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh164_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh164_datafim_dia"] !="") ){ 
       $sql  .= $virgula." rh164_datafim = '$this->rh164_datafim' ";
       $virgula = ",";
       if(trim($this->rh164_datafim) == null ){ 
         $this->erro_sql = " Campo Data término não informado.";
         $this->erro_campo = "rh164_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh164_datafim_dia"])){ 
         $sql  .= $virgula." rh164_datafim = null ";
         $virgula = ",";
         if(trim($this->rh164_datafim) == null ){ 
           $this->erro_sql = " Campo Data término não informado.";
           $this->erro_campo = "rh164_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($rh164_sequencial!=null){
       $sql .= " rh164_sequencial = $this->rh164_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh164_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21196,'$this->rh164_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh164_sequencial"]) || $this->rh164_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3817,21196,'".AddSlashes(pg_result($resaco,$conresaco,'rh164_sequencial'))."','$this->rh164_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh164_contratoemergencial"]) || $this->rh164_contratoemergencial != "")
             $resac = db_query("insert into db_acount values($acount,3817,21197,'".AddSlashes(pg_result($resaco,$conresaco,'rh164_contratoemergencial'))."','$this->rh164_contratoemergencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh164_descricao"]) || $this->rh164_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3817,21198,'".AddSlashes(pg_result($resaco,$conresaco,'rh164_descricao'))."','$this->rh164_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh164_datainicio"]) || $this->rh164_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,3817,21199,'".AddSlashes(pg_result($resaco,$conresaco,'rh164_datainicio'))."','$this->rh164_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh164_datafim"]) || $this->rh164_datafim != "")
             $resac = db_query("insert into db_acount values($acount,3817,21200,'".AddSlashes(pg_result($resaco,$conresaco,'rh164_datafim'))."','$this->rh164_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Renovações Contrato Emergencial não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh164_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Renovações Contrato Emergencial não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh164_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh164_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh164_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh164_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21196,'$rh164_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3817,21196,'','".AddSlashes(pg_result($resaco,$iresaco,'rh164_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3817,21197,'','".AddSlashes(pg_result($resaco,$iresaco,'rh164_contratoemergencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3817,21198,'','".AddSlashes(pg_result($resaco,$iresaco,'rh164_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3817,21199,'','".AddSlashes(pg_result($resaco,$iresaco,'rh164_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3817,21200,'','".AddSlashes(pg_result($resaco,$iresaco,'rh164_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhcontratoemergencialrenovacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh164_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh164_sequencial = $rh164_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Renovações Contrato Emergencial não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh164_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Renovações Contrato Emergencial não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh164_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh164_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rhcontratoemergencialrenovacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh164_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhcontratoemergencialrenovacao ";
     $sql .= "      inner join rhcontratoemergencial  on  rhcontratoemergencial.rh163_sequencial = rhcontratoemergencialrenovacao.rh164_contratoemergencial";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh164_sequencial)) {
         $sql2 .= " where rhcontratoemergencialrenovacao.rh164_sequencial = $rh164_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($rh164_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhcontratoemergencialrenovacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh164_sequencial)){
         $sql2 .= " where rhcontratoemergencialrenovacao.rh164_sequencial = $rh164_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

}
