<?
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

//MODULO: material
//CLASSE DA ENTIDADE matunid
class cl_matunid { 
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
   var $m61_codmatunid = 0; 
   var $m61_descr = null; 
   var $m61_usaquant = 'f'; 
   var $m61_abrev = null; 
   var $m61_usadec = 'f'; 
   var $m61_codigotribunal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m61_codmatunid = int8 = Código da unidade 
                 m61_descr = varchar(40) = Descrição da unidade
                 m61_usaquant = bool = Se usa quantidade da unidade 
                 m61_abrev = varchar(6) = Abreviatura da descrição 
                 m61_usadec = bool = Aceita casas decimais 
                 m61_codigotribunal = varchar(5) = Código do Tribunal 
                 ";
   //funcao construtor da classe 
   function cl_matunid() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matunid"); 
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
       $this->m61_codmatunid = ($this->m61_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_codmatunid"]:$this->m61_codmatunid);
       $this->m61_descr = ($this->m61_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_descr"]:$this->m61_descr);
       $this->m61_usaquant = ($this->m61_usaquant == "f"?@$GLOBALS["HTTP_POST_VARS"]["m61_usaquant"]:$this->m61_usaquant);
       $this->m61_abrev = ($this->m61_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_abrev"]:$this->m61_abrev);
       $this->m61_usadec = ($this->m61_usadec == "f"?@$GLOBALS["HTTP_POST_VARS"]["m61_usadec"]:$this->m61_usadec);
       $this->m61_codigotribunal = ($this->m61_codigotribunal == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_codigotribunal"]:$this->m61_codigotribunal);
     }else{
       $this->m61_codmatunid = ($this->m61_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m61_codmatunid"]:$this->m61_codmatunid);
     }
   }
   // funcao para Inclusão
   function incluir ($m61_codmatunid){ 
      $this->atualizacampos();
     if($this->m61_descr == null ){ 
       $this->erro_sql = " Campo Descrição da unidade não informado.";
       $this->erro_campo = "m61_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m61_usaquant == null ){ 
       $this->erro_sql = " Campo Se usa quantidade da unidade não informado.";
       $this->erro_campo = "m61_usaquant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m61_abrev == null ){ 
       $this->erro_sql = " Campo Abreviatura da descrição não informado.";
       $this->erro_campo = "m61_abrev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m61_usadec == null ){ 
       $this->erro_sql = " Campo Aceita casas decimais não informado.";
       $this->erro_campo = "m61_usadec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m61_codmatunid == "" || $m61_codmatunid == null ){
       $result = db_query("select nextval('matunid_m61_codmatunid_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matunid_m61_codmatunid_seq do campo: m61_codmatunid"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m61_codmatunid = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matunid_m61_codmatunid_seq");
       if(($result != false) && (pg_result($result,0,0) < $m61_codmatunid)){
         $this->erro_sql = " Campo m61_codmatunid maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m61_codmatunid = $m61_codmatunid; 
       }
     }
     if(($this->m61_codmatunid == null) || ($this->m61_codmatunid == "") ){ 
       $this->erro_sql = " Campo m61_codmatunid não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matunid(
                                       m61_codmatunid 
                                      ,m61_descr 
                                      ,m61_usaquant 
                                      ,m61_abrev 
                                      ,m61_usadec 
                                      ,m61_codigotribunal 
                       )
                values (
                                $this->m61_codmatunid 
                               ,'$this->m61_descr' 
                               ,'$this->m61_usaquant' 
                               ,'$this->m61_abrev' 
                               ,'$this->m61_usadec' 
                               ,'$this->m61_codigotribunal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Unidades dos materiais ($this->m61_codmatunid) não incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Unidades dos materiais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Unidades dos materiais ($this->m61_codmatunid) não incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m61_codmatunid;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m61_codmatunid  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6262,'$this->m61_codmatunid','I')");
         $resac = db_query("insert into db_acount values($acount,1017,6262,'','".AddSlashes(pg_result($resaco,0,'m61_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,6263,'','".AddSlashes(pg_result($resaco,0,'m61_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,6461,'','".AddSlashes(pg_result($resaco,0,'m61_usaquant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,6603,'','".AddSlashes(pg_result($resaco,0,'m61_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,8637,'','".AddSlashes(pg_result($resaco,0,'m61_usadec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1017,21766,'','".AddSlashes(pg_result($resaco,0,'m61_codigotribunal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($m61_codmatunid=null) { 
      $this->atualizacampos();
     $sql = " update matunid set ";
     $virgula = "";
     if(trim($this->m61_codmatunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_codmatunid"])){ 
       $sql  .= $virgula." m61_codmatunid = $this->m61_codmatunid ";
       $virgula = ",";
       if(trim($this->m61_codmatunid) == null ){ 
         $this->erro_sql = " Campo Código da unidade não informado.";
         $this->erro_campo = "m61_codmatunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_descr"])){ 
       $sql  .= $virgula." m61_descr = '$this->m61_descr' ";
       $virgula = ",";
       if(trim($this->m61_descr) == null ){ 
         $this->erro_sql = " Campo Unidade não informado.";
         $this->erro_campo = "m61_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_usaquant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_usaquant"])){ 
       $sql  .= $virgula." m61_usaquant = '$this->m61_usaquant' ";
       $virgula = ",";
       if(trim($this->m61_usaquant) == null ){ 
         $this->erro_sql = " Campo Se usa quantidade da unidade não informado.";
         $this->erro_campo = "m61_usaquant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_abrev"])){ 
       $sql  .= $virgula." m61_abrev = '$this->m61_abrev' ";
       $virgula = ",";
       if(trim($this->m61_abrev) == null ){ 
         $this->erro_sql = " Campo Abreviatura da descrição não informado.";
         $this->erro_campo = "m61_abrev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_usadec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_usadec"])){ 
       $sql  .= $virgula." m61_usadec = '$this->m61_usadec' ";
       $virgula = ",";
       if(trim($this->m61_usadec) == null ){ 
         $this->erro_sql = " Campo Aceita casas decimais não informado.";
         $this->erro_campo = "m61_usadec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m61_codigotribunal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m61_codigotribunal"])){ 
       $sql  .= $virgula." m61_codigotribunal = '$this->m61_codigotribunal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m61_codmatunid!=null){
       $sql .= " m61_codmatunid = $this->m61_codmatunid";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m61_codmatunid));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,6262,'$this->m61_codmatunid','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m61_codmatunid"]) || $this->m61_codmatunid != "")
             $resac = db_query("insert into db_acount values($acount,1017,6262,'".AddSlashes(pg_result($resaco,$conresaco,'m61_codmatunid'))."','$this->m61_codmatunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m61_descr"]) || $this->m61_descr != "")
             $resac = db_query("insert into db_acount values($acount,1017,6263,'".AddSlashes(pg_result($resaco,$conresaco,'m61_descr'))."','$this->m61_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m61_usaquant"]) || $this->m61_usaquant != "")
             $resac = db_query("insert into db_acount values($acount,1017,6461,'".AddSlashes(pg_result($resaco,$conresaco,'m61_usaquant'))."','$this->m61_usaquant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m61_abrev"]) || $this->m61_abrev != "")
             $resac = db_query("insert into db_acount values($acount,1017,6603,'".AddSlashes(pg_result($resaco,$conresaco,'m61_abrev'))."','$this->m61_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m61_usadec"]) || $this->m61_usadec != "")
             $resac = db_query("insert into db_acount values($acount,1017,8637,'".AddSlashes(pg_result($resaco,$conresaco,'m61_usadec'))."','$this->m61_usadec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m61_codigotribunal"]) || $this->m61_codigotribunal != "")
             $resac = db_query("insert into db_acount values($acount,1017,21766,'".AddSlashes(pg_result($resaco,$conresaco,'m61_codigotribunal'))."','$this->m61_codigotribunal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades dos materiais não alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m61_codmatunid;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Unidades dos materiais não foi alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m61_codmatunid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m61_codmatunid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($m61_codmatunid=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($m61_codmatunid));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,6262,'$m61_codmatunid','E')");
           $resac  = db_query("insert into db_acount values($acount,1017,6262,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1017,6263,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1017,6461,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_usaquant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1017,6603,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1017,8637,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_usadec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1017,21766,'','".AddSlashes(pg_result($resaco,$iresaco,'m61_codigotribunal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from matunid
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($m61_codmatunid)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " m61_codmatunid = $m61_codmatunid ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidades dos materiais não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m61_codmatunid;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Unidades dos materiais não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m61_codmatunid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m61_codmatunid;
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
        $this->erro_sql   = "Record Vazio na Tabela:matunid";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($m61_codmatunid = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from matunid ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($m61_codmatunid)) {
         $sql2 .= " where matunid.m61_codmatunid = $m61_codmatunid "; 
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
   public function sql_query_file ($m61_codmatunid = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from matunid ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($m61_codmatunid)){
         $sql2 .= " where matunid.m61_codmatunid = $m61_codmatunid "; 
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
