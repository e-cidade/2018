<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: secretariadeeducacao
//CLASSE DA ENTIDADE sec_parametros
class cl_sec_parametros { 
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
   var $ed290_sequencial = 0; 
   var $ed290_importcenso = 0; 
   var $ed290_controleprogressaoparcial = 0; 
   var $ed290_diasmanutencaohistorico = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed290_sequencial = int4 = Sequencial 
                 ed290_importcenso = int4 = Importação do Censo 
                 ed290_controleprogressaoparcial = int4 = Controle da Progressao Parcial 
                 ed290_diasmanutencaohistorico = int4 = Dias para Manutenção do Histórico 
                 ";
   //funcao construtor da classe 
   function cl_sec_parametros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sec_parametros"); 
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
       $this->ed290_sequencial = ($this->ed290_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed290_sequencial"]:$this->ed290_sequencial);
       $this->ed290_importcenso = ($this->ed290_importcenso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed290_importcenso"]:$this->ed290_importcenso);
       $this->ed290_controleprogressaoparcial = ($this->ed290_controleprogressaoparcial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed290_controleprogressaoparcial"]:$this->ed290_controleprogressaoparcial);
       $this->ed290_diasmanutencaohistorico = ($this->ed290_diasmanutencaohistorico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed290_diasmanutencaohistorico"]:$this->ed290_diasmanutencaohistorico);
     }else{
       $this->ed290_sequencial = ($this->ed290_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed290_sequencial"]:$this->ed290_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ed290_sequencial){ 
      $this->atualizacampos();
     if($this->ed290_importcenso == null ){ 
       $this->erro_sql = " Campo Importação do Censo não informado.";
       $this->erro_campo = "ed290_importcenso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed290_controleprogressaoparcial == null ){ 
       $this->erro_sql = " Campo Controle da Progressao Parcial não informado.";
       $this->erro_campo = "ed290_controleprogressaoparcial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed290_diasmanutencaohistorico == null ){ 
       $this->erro_sql = " Campo Dias para Manutenção do Histórico não informado.";
       $this->erro_campo = "ed290_diasmanutencaohistorico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed290_sequencial == "" || $ed290_sequencial == null ){
       $result = db_query("select nextval('sec_parametros_ed290_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sec_parametros_ed290_sequencial_seq do campo: ed290_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed290_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sec_parametros_ed290_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed290_sequencial)){
         $this->erro_sql = " Campo ed290_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed290_sequencial = $ed290_sequencial; 
       }
     }
     if(($this->ed290_sequencial == null) || ($this->ed290_sequencial == "") ){ 
       $this->erro_sql = " Campo ed290_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sec_parametros(
                                       ed290_sequencial 
                                      ,ed290_importcenso 
                                      ,ed290_controleprogressaoparcial 
                                      ,ed290_diasmanutencaohistorico 
                       )
                values (
                                $this->ed290_sequencial 
                               ,$this->ed290_importcenso 
                               ,$this->ed290_controleprogressaoparcial 
                               ,$this->ed290_diasmanutencaohistorico 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Secretaria Parametros ($this->ed290_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Secretaria Parametros já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Secretaria Parametros ($this->ed290_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed290_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed290_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17998,'$this->ed290_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3180,17998,'','".AddSlashes(pg_result($resaco,0,'ed290_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3180,17999,'','".AddSlashes(pg_result($resaco,0,'ed290_importcenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3180,19536,'','".AddSlashes(pg_result($resaco,0,'ed290_controleprogressaoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3180,21782,'','".AddSlashes(pg_result($resaco,0,'ed290_diasmanutencaohistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed290_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update sec_parametros set ";
     $virgula = "";
     if(trim($this->ed290_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed290_sequencial"])){ 
       $sql  .= $virgula." ed290_sequencial = $this->ed290_sequencial ";
       $virgula = ",";
       if(trim($this->ed290_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ed290_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed290_importcenso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed290_importcenso"])){ 
       $sql  .= $virgula." ed290_importcenso = $this->ed290_importcenso ";
       $virgula = ",";
       if(trim($this->ed290_importcenso) == null ){ 
         $this->erro_sql = " Campo Importação do Censo não informado.";
         $this->erro_campo = "ed290_importcenso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed290_controleprogressaoparcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed290_controleprogressaoparcial"])){ 
       $sql  .= $virgula." ed290_controleprogressaoparcial = $this->ed290_controleprogressaoparcial ";
       $virgula = ",";
       if(trim($this->ed290_controleprogressaoparcial) == null ){ 
         $this->erro_sql = " Campo Controle da Progressao Parcial não informado.";
         $this->erro_campo = "ed290_controleprogressaoparcial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed290_diasmanutencaohistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed290_diasmanutencaohistorico"])){ 
       $sql  .= $virgula." ed290_diasmanutencaohistorico = $this->ed290_diasmanutencaohistorico ";
       $virgula = ",";
       if(trim($this->ed290_diasmanutencaohistorico) == null ){ 
         $this->erro_sql = " Campo Dias para Manutenção do Histórico não informado.";
         $this->erro_campo = "ed290_diasmanutencaohistorico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed290_sequencial!=null){
       $sql .= " ed290_sequencial = $this->ed290_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed290_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17998,'$this->ed290_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed290_sequencial"]) || $this->ed290_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3180,17998,'".AddSlashes(pg_result($resaco,$conresaco,'ed290_sequencial'))."','$this->ed290_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed290_importcenso"]) || $this->ed290_importcenso != "")
             $resac = db_query("insert into db_acount values($acount,3180,17999,'".AddSlashes(pg_result($resaco,$conresaco,'ed290_importcenso'))."','$this->ed290_importcenso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed290_controleprogressaoparcial"]) || $this->ed290_controleprogressaoparcial != "")
             $resac = db_query("insert into db_acount values($acount,3180,19536,'".AddSlashes(pg_result($resaco,$conresaco,'ed290_controleprogressaoparcial'))."','$this->ed290_controleprogressaoparcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed290_diasmanutencaohistorico"]) || $this->ed290_diasmanutencaohistorico != "")
             $resac = db_query("insert into db_acount values($acount,3180,21782,'".AddSlashes(pg_result($resaco,$conresaco,'ed290_diasmanutencaohistorico'))."','$this->ed290_diasmanutencaohistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Secretaria Parametros não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed290_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Secretaria Parametros não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed290_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed290_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed290_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed290_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,17998,'$ed290_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3180,17998,'','".AddSlashes(pg_result($resaco,$iresaco,'ed290_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3180,17999,'','".AddSlashes(pg_result($resaco,$iresaco,'ed290_importcenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3180,19536,'','".AddSlashes(pg_result($resaco,$iresaco,'ed290_controleprogressaoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3180,21782,'','".AddSlashes(pg_result($resaco,$iresaco,'ed290_diasmanutencaohistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sec_parametros
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed290_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed290_sequencial = $ed290_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Secretaria Parametros não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed290_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Secretaria Parametros não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed290_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed290_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:sec_parametros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed290_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sec_parametros ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed290_sequencial!=null ){
         $sql2 .= " where sec_parametros.ed290_sequencial = $ed290_sequencial "; 
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
   function sql_query_file ( $ed290_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sec_parametros ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed290_sequencial!=null ){
         $sql2 .= " where sec_parametros.ed290_sequencial = $ed290_sequencial "; 
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