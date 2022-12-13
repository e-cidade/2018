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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_depart
class cl_db_depart { 
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
   var $coddepto = 0; 
   var $descrdepto = null; 
   var $nomeresponsavel = null; 
   var $emailresponsavel = null; 
   var $limite_dia = null; 
   var $limite_mes = null; 
   var $limite_ano = null; 
   var $limite = null; 
   var $fonedepto = null; 
   var $emaildepto = null; 
   var $faxdepto = null; 
   var $ramaldepto = null; 
   var $instit = 0; 
   var $id_usuarioresp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 coddepto = int4 = Depart. 
                 descrdepto = varchar(40) = Descrição do Departamento 
                 nomeresponsavel = varchar(40) = Nome Responsável 
                 emailresponsavel = varchar(50) = Email Responsável 
                 limite = date = Data limite 
                 fonedepto = varchar(12) = Telefone 
                 emaildepto = varchar(50) = E-mail 
                 faxdepto = varchar(12) = Fax 
                 ramaldepto = varchar(10) = Ramal 
                 instit = int4 = Instituição 
                 id_usuarioresp = int4 = Login Responsável 
                 ";
   //funcao construtor da classe 
   function cl_db_depart() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_depart"); 
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
       $this->coddepto = ($this->coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["coddepto"]:$this->coddepto);
       $this->descrdepto = ($this->descrdepto == ""?@$GLOBALS["HTTP_POST_VARS"]["descrdepto"]:$this->descrdepto);
       $this->nomeresponsavel = ($this->nomeresponsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["nomeresponsavel"]:$this->nomeresponsavel);
       $this->emailresponsavel = ($this->emailresponsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["emailresponsavel"]:$this->emailresponsavel);
       if($this->limite == ""){
         $this->limite_dia = ($this->limite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["limite_dia"]:$this->limite_dia);
         $this->limite_mes = ($this->limite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["limite_mes"]:$this->limite_mes);
         $this->limite_ano = ($this->limite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["limite_ano"]:$this->limite_ano);
         if($this->limite_dia != ""){
            $this->limite = $this->limite_ano."-".$this->limite_mes."-".$this->limite_dia;
         }
       }
       $this->fonedepto = ($this->fonedepto == ""?@$GLOBALS["HTTP_POST_VARS"]["fonedepto"]:$this->fonedepto);
       $this->emaildepto = ($this->emaildepto == ""?@$GLOBALS["HTTP_POST_VARS"]["emaildepto"]:$this->emaildepto);
       $this->faxdepto = ($this->faxdepto == ""?@$GLOBALS["HTTP_POST_VARS"]["faxdepto"]:$this->faxdepto);
       $this->ramaldepto = ($this->ramaldepto == ""?@$GLOBALS["HTTP_POST_VARS"]["ramaldepto"]:$this->ramaldepto);
       $this->instit = ($this->instit == ""?@$GLOBALS["HTTP_POST_VARS"]["instit"]:$this->instit);
       $this->id_usuarioresp = ($this->id_usuarioresp == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuarioresp"]:$this->id_usuarioresp);
     }else{
       $this->coddepto = ($this->coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["coddepto"]:$this->coddepto);
     }
   }
   // funcao para Inclusão
   function incluir ($coddepto){ 
      $this->atualizacampos();
     if($this->descrdepto == null ){ 
       $this->erro_sql = " Campo Descrição do Departamento não informado.";
       $this->erro_campo = "descrdepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->limite == null ){ 
       $this->limite = "null";
     }
     if($this->instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($coddepto == "" || $coddepto == null ){
       $result = db_query("select nextval('db_depart_coddepto_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_depart_coddepto_seq do campo: coddepto"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->coddepto = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_depart_coddepto_seq");
       if(($result != false) && (pg_result($result,0,0) < $coddepto)){
         $this->erro_sql = " Campo coddepto maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->coddepto = $coddepto; 
       }
     }
     if(($this->coddepto == null) || ($this->coddepto == "") ){ 
       $this->erro_sql = " Campo coddepto não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if(trim($this->id_usuarioresp) == "") {
       $this->id_usuarioresp = 'null';
     }

     $sql = "insert into db_depart(
                                       coddepto 
                                      ,descrdepto 
                                      ,nomeresponsavel 
                                      ,emailresponsavel 
                                      ,limite 
                                      ,fonedepto 
                                      ,emaildepto 
                                      ,faxdepto 
                                      ,ramaldepto 
                                      ,instit 
                                      ,id_usuarioresp 
                       )
                values (
                                $this->coddepto 
                               ,'$this->descrdepto' 
                               ,'$this->nomeresponsavel' 
                               ,'$this->emailresponsavel' 
                               ,".($this->limite == "null" || $this->limite == ""?"null":"'".$this->limite."'")." 
                               ,'$this->fonedepto' 
                               ,'$this->emaildepto' 
                               ,'$this->faxdepto' 
                               ,'$this->ramaldepto' 
                               ,$this->instit 
                               ,$this->id_usuarioresp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Departamento dos Usuários ($this->coddepto) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Departamento dos Usuários já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Departamento dos Usuários ($this->coddepto) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->coddepto;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->coddepto  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,814,'$this->coddepto','I')");
         $resac = db_query("insert into db_acount values($acount,154,814,'','".AddSlashes(pg_result($resaco,0,'coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,815,'','".AddSlashes(pg_result($resaco,0,'descrdepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,816,'','".AddSlashes(pg_result($resaco,0,'nomeresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,817,'','".AddSlashes(pg_result($resaco,0,'emailresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,6524,'','".AddSlashes(pg_result($resaco,0,'limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,7285,'','".AddSlashes(pg_result($resaco,0,'fonedepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,7284,'','".AddSlashes(pg_result($resaco,0,'emaildepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,7283,'','".AddSlashes(pg_result($resaco,0,'faxdepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,7286,'','".AddSlashes(pg_result($resaco,0,'ramaldepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,9996,'','".AddSlashes(pg_result($resaco,0,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,154,18025,'','".AddSlashes(pg_result($resaco,0,'id_usuarioresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($coddepto=null) { 
      $this->atualizacampos();
     $sql = " update db_depart set ";
     $virgula = "";
     if(trim($this->coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["coddepto"])){ 
       $sql  .= $virgula." coddepto = $this->coddepto ";
       $virgula = ",";
       if(trim($this->coddepto) == null ){ 
         $this->erro_sql = " Campo Depart. não informado.";
         $this->erro_campo = "coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descrdepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descrdepto"])){ 
       $sql  .= $virgula." descrdepto = '$this->descrdepto' ";
       $virgula = ",";
       if(trim($this->descrdepto) == null ){ 
         $this->erro_sql = " Campo Descrição do Departamento não informado.";
         $this->erro_campo = "descrdepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomeresponsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomeresponsavel"])){ 
       $sql  .= $virgula." nomeresponsavel = '$this->nomeresponsavel' ";
       $virgula = ",";
     }
     if(trim($this->emailresponsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["emailresponsavel"])){ 
       $sql  .= $virgula." emailresponsavel = '$this->emailresponsavel' ";
       $virgula = ",";
     }
     if(trim($this->limite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["limite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["limite_dia"] !="") ){ 
       $sql  .= $virgula." limite = '$this->limite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["limite_dia"])){ 
         $sql  .= $virgula." limite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->fonedepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fonedepto"])){ 
       $sql  .= $virgula." fonedepto = '$this->fonedepto' ";
       $virgula = ",";
     }
     if(trim($this->emaildepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["emaildepto"])){ 
       $sql  .= $virgula." emaildepto = '$this->emaildepto' ";
       $virgula = ",";
     }
     if(trim($this->faxdepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["faxdepto"])){ 
       $sql  .= $virgula." faxdepto = '$this->faxdepto' ";
       $virgula = ",";
     }
     if(trim($this->ramaldepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ramaldepto"])){ 
       $sql  .= $virgula." ramaldepto = '$this->ramaldepto' ";
       $virgula = ",";
     }
     if(trim($this->instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["instit"])){ 
       $sql  .= $virgula." instit = $this->instit ";
       $virgula = ",";
       if(trim($this->instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_usuarioresp)!=""){
       $sql  .= $virgula." id_usuarioresp = $this->id_usuarioresp ";
       $virgula = ",";
     }

     if(trim($this->id_usuarioresp) == "") {

       $sql  .= $virgula." id_usuarioresp = null ";
       $sql  .= $virgula." nomeresponsavel = '' ";
       $virgula = ",";
     }


     $sql .= " where ";
     if($coddepto!=null){
       $sql .= " coddepto = $this->coddepto";
     }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->coddepto));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,814,'$this->coddepto','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["coddepto"]) || $this->coddepto != "")
             $resac = db_query("insert into db_acount values($acount,154,814,'".AddSlashes(pg_result($resaco,$conresaco,'coddepto'))."','$this->coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["descrdepto"]) || $this->descrdepto != "")
             $resac = db_query("insert into db_acount values($acount,154,815,'".AddSlashes(pg_result($resaco,$conresaco,'descrdepto'))."','$this->descrdepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["nomeresponsavel"]) || $this->nomeresponsavel != "")
             $resac = db_query("insert into db_acount values($acount,154,816,'".AddSlashes(pg_result($resaco,$conresaco,'nomeresponsavel'))."','$this->nomeresponsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["emailresponsavel"]) || $this->emailresponsavel != "")
             $resac = db_query("insert into db_acount values($acount,154,817,'".AddSlashes(pg_result($resaco,$conresaco,'emailresponsavel'))."','$this->emailresponsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["limite"]) || $this->limite != "")
             $resac = db_query("insert into db_acount values($acount,154,6524,'".AddSlashes(pg_result($resaco,$conresaco,'limite'))."','$this->limite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fonedepto"]) || $this->fonedepto != "")
             $resac = db_query("insert into db_acount values($acount,154,7285,'".AddSlashes(pg_result($resaco,$conresaco,'fonedepto'))."','$this->fonedepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["emaildepto"]) || $this->emaildepto != "")
             $resac = db_query("insert into db_acount values($acount,154,7284,'".AddSlashes(pg_result($resaco,$conresaco,'emaildepto'))."','$this->emaildepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["faxdepto"]) || $this->faxdepto != "")
             $resac = db_query("insert into db_acount values($acount,154,7283,'".AddSlashes(pg_result($resaco,$conresaco,'faxdepto'))."','$this->faxdepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ramaldepto"]) || $this->ramaldepto != "")
             $resac = db_query("insert into db_acount values($acount,154,7286,'".AddSlashes(pg_result($resaco,$conresaco,'ramaldepto'))."','$this->ramaldepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["instit"]) || $this->instit != "")
             $resac = db_query("insert into db_acount values($acount,154,9996,'".AddSlashes(pg_result($resaco,$conresaco,'instit'))."','$this->instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["id_usuarioresp"]) || $this->id_usuarioresp != "")
             $resac = db_query("insert into db_acount values($acount,154,18025,'".AddSlashes(pg_result($resaco,$conresaco,'id_usuarioresp'))."','$this->id_usuarioresp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Departamento dos Usuários não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->coddepto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Departamento dos Usuários não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->coddepto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->coddepto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($coddepto=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($coddepto));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,814,'$coddepto','E')");
           $resac  = db_query("insert into db_acount values($acount,154,814,'','".AddSlashes(pg_result($resaco,$iresaco,'coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,815,'','".AddSlashes(pg_result($resaco,$iresaco,'descrdepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,816,'','".AddSlashes(pg_result($resaco,$iresaco,'nomeresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,817,'','".AddSlashes(pg_result($resaco,$iresaco,'emailresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,6524,'','".AddSlashes(pg_result($resaco,$iresaco,'limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,7285,'','".AddSlashes(pg_result($resaco,$iresaco,'fonedepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,7284,'','".AddSlashes(pg_result($resaco,$iresaco,'emaildepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,7283,'','".AddSlashes(pg_result($resaco,$iresaco,'faxdepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,7286,'','".AddSlashes(pg_result($resaco,$iresaco,'ramaldepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,9996,'','".AddSlashes(pg_result($resaco,$iresaco,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,154,18025,'','".AddSlashes(pg_result($resaco,$iresaco,'id_usuarioresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_depart
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($coddepto)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " coddepto = $coddepto ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Departamento dos Usuários não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$coddepto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Departamento dos Usuários não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$coddepto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$coddepto;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_depart";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($coddepto = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= " from db_depart ";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
//     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_depart.id_usuarioresp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($coddepto)) {
         $sql2 .= " where db_depart.coddepto = $coddepto "; 
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
   public function sql_query_file ($coddepto = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_depart ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($coddepto)){
         $sql2 .= " where db_depart.coddepto = $coddepto "; 
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

   function sql_query_almox ( $coddepto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_depart ";
     $sql .= "      left join db_almoxdepto on db_almoxdepto.m92_depto = db_depart.coddepto";
     $sql .= "      left join db_almox on db_almox.m91_codigo          = db_almoxdepto.m92_codalmox";
     $sql2 = "";
     if($dbwhere==""){
       if($coddepto!=null ){
         $sql2 .= " where db_depart.coddepto = $coddepto "; 
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
   function sql_query_div ( $coddepto=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_depart ";
     $sql .= "      inner join db_departorg on db_departorg.db01_coddepto = db_depart.coddepto";
     $sql .= "      inner join orcorgao     on orcorgao.o40_orgao         = db_departorg.db01_orgao and
                                               orcorgao.o40_anousu        = db_departorg.db01_anousu";
     $sql .= "      left  join departdiv    on departdiv.t30_depto        = db_depart.coddepto";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($coddepto!=null ){
         $sql2 .= " where db_depart.coddepto = $coddepto ";
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

   function sql_query_deptousuario ( $coddepto=null,$campos="*",$ordem=null,$dbwhere="", $iUsuario = ''){

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
     $sql .= " from db_depart ";
     $sql .= "      inner join db_config  on  db_config.codigo    = db_depart.instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm            = db_config.numcgm";
     $sql .= "      left join db_depusu on  db_depusu.coddepto    = db_depart.coddepto ";
     $sql .= "                         and  db_depusu.id_usuario  = {$iUsuario}";
     $sql2 = "";
     if($dbwhere==""){
       if($coddepto!=null ){
         $sql2 .= " where db_depart.coddepto = $coddepto ";
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
   function sql_query_departamento_divisao ( $coddepto=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from db_depart ";
  	$sql .= "      inner join departdiv    on departdiv.t30_depto        = db_depart.coddepto";
  	$sql .= "      inner join db_config  on  db_config.codigo            = db_depart.instit";
  	$sql .= "      inner join cgm  on  cgm.z01_numcgm                    = db_config.numcgm";
  	$sql2 = "";

  	if($dbwhere==""){
  		if($coddepto!=null ){
  			$sql2 .= " where db_depart.coddepto = $coddepto ";
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
  function sql_query_dados_depart ( $coddepto=null,$campos="*",$ordem=null,$dbwhere=""){

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
    $sql .= " from db_depart ";
    $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
    $sql2 = "";
    if($dbwhere==""){
      if($coddepto!=null ){
        $sql2 .= " where db_depart.coddepto = $coddepto ";
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

   public function sql_query_unidades ($coddepto = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_depart ";
     $sql .= "       left join unidades on sd02_i_codigo = coddepto";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($coddepto)){
         $sql2 .= " where db_depart.coddepto = $coddepto ";
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