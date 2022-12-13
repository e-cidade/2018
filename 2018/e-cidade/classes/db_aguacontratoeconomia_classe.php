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

//MODULO: agua
//CLASSE DA ENTIDADE aguacontratoeconomia
class cl_aguacontratoeconomia { 
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
   var $x38_sequencial = 0; 
   var $x38_aguacontrato = 0; 
   var $x38_cgm = 0; 
   var $x38_aguacategoriaconsumo = 0; 
   var $x38_datavalidadecadastro_dia = null; 
   var $x38_datavalidadecadastro_mes = null; 
   var $x38_datavalidadecadastro_ano = null; 
   var $x38_datavalidadecadastro = null; 
   var $x38_nis = null; 
   var $x38_emitiroutrosdebitos = 'f'; 
   var $x38_complemento = null; 
   var $x38_observacoes = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x38_sequencial = int4 = Código da Economia 
                 x38_aguacontrato = int4 = Código do Contrato 
                 x38_cgm = int4 = Nome/Razão Social 
                 x38_aguacategoriaconsumo = int4 = Categoria de Consumo 
                 x38_datavalidadecadastro = date = Data de Validade do Cadastro 
                 x38_nis = varchar(20) = Número de Identificação Social 
                 x38_emitiroutrosdebitos = bool = Emitir Outros Débitos 
                 x38_complemento = varchar(200) = Complemento 
                 x38_observacoes = varchar(200) = Observações 
                 ";
   //funcao construtor da classe 
   function cl_aguacontratoeconomia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacontratoeconomia"); 
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

   // funcao para Inclusão
   function incluir ($x38_sequencial){ 

     if($this->x38_aguacontrato == null ){ 
       $this->erro_sql = " Campo Código do Contrato não informado.";
       $this->erro_campo = "x38_aguacontrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x38_cgm == null ){ 
       $this->erro_sql = " Campo Nome/Razão Social não informado.";
       $this->erro_campo = "x38_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x38_aguacategoriaconsumo == null ){ 
       $this->erro_sql = " Campo Categoria de Consumo não informado.";
       $this->erro_campo = "x38_aguacategoriaconsumo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x38_sequencial == "" || $x38_sequencial == null ){
       $result = db_query("select nextval('aguacontratoeconomia_x38_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacontratoeconomia_x38_sequencial_seq do campo: x38_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x38_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacontratoeconomia_x38_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x38_sequencial)){
         $this->erro_sql = " Campo x38_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x38_sequencial = $x38_sequencial; 
       }
     }
     if(($this->x38_sequencial == null) || ($this->x38_sequencial == "") ){ 
       $this->erro_sql = " Campo x38_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacontratoeconomia(
                                       x38_sequencial 
                                      ,x38_aguacontrato 
                                      ,x38_cgm 
                                      ,x38_aguacategoriaconsumo 
                                      ,x38_datavalidadecadastro 
                                      ,x38_nis 
                                      ,x38_emitiroutrosdebitos 
                                      ,x38_complemento 
                                      ,x38_observacoes 
                       )
                values (
                                $this->x38_sequencial 
                               ,$this->x38_aguacontrato 
                               ,$this->x38_cgm 
                               ,$this->x38_aguacategoriaconsumo 
                               ,".($this->x38_datavalidadecadastro == "null" || $this->x38_datavalidadecadastro == ""?"null":"'".$this->x38_datavalidadecadastro."'")." 
                               ,'$this->x38_nis' 
                               ,'$this->x38_emitiroutrosdebitos' 
                               ,'$this->x38_complemento' 
                               ,'$this->x38_observacoes' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agua Contrato Economia ($this->x38_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agua Contrato Economia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agua Contrato Economia ($this->x38_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->x38_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x38_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22113,'$this->x38_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3983,22113,'','".AddSlashes(pg_result($resaco,0,'x38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3983,22114,'','".AddSlashes(pg_result($resaco,0,'x38_aguacontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3983,22115,'','".AddSlashes(pg_result($resaco,0,'x38_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3983,22116,'','".AddSlashes(pg_result($resaco,0,'x38_aguacategoriaconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3983,22117,'','".AddSlashes(pg_result($resaco,0,'x38_datavalidadecadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3983,22118,'','".AddSlashes(pg_result($resaco,0,'x38_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3983,1009273,'','".AddSlashes(pg_result($resaco,0,'x38_emitiroutrosdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3983,1009274,'','".AddSlashes(pg_result($resaco,0,'x38_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3983,1009393,'','".AddSlashes(pg_result($resaco,0,'x38_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($x38_sequencial=null) { 

     $sql = " update aguacontratoeconomia set ";
     $virgula = "";
     if(trim($this->x38_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x38_sequencial"])){ 
       $sql  .= $virgula." x38_sequencial = $this->x38_sequencial ";
       $virgula = ",";
       if(trim($this->x38_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da Economia não informado.";
         $this->erro_campo = "x38_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->x38_aguacontrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x38_aguacontrato"])){ 
       $sql  .= $virgula." x38_aguacontrato = $this->x38_aguacontrato ";
       $virgula = ",";
       if(trim($this->x38_aguacontrato) == null ){ 
         $this->erro_sql = " Campo Código do Contrato não informado.";
         $this->erro_campo = "x38_aguacontrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->x38_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x38_cgm"])){ 
       $sql  .= $virgula." x38_cgm = $this->x38_cgm ";
       $virgula = ",";
       if(trim($this->x38_cgm) == null ){ 
         $this->erro_sql = " Campo Nome/Razão Social não informado.";
         $this->erro_campo = "x38_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->x38_aguacategoriaconsumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x38_aguacategoriaconsumo"])){ 
       $sql  .= $virgula." x38_aguacategoriaconsumo = $this->x38_aguacategoriaconsumo ";
       $virgula = ",";
       if(trim($this->x38_aguacategoriaconsumo) == null ){ 
         $this->erro_sql = " Campo Categoria de Consumo não informado.";
         $this->erro_campo = "x38_aguacategoriaconsumo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if($this->x38_datavalidadecadastro !== null) {
       $sql  .= $virgula." x38_datavalidadecadastro = '$this->x38_datavalidadecadastro' ";
       $virgula = ",";
     } else { 
         $sql  .= $virgula." x38_datavalidadecadastro = null ";
         $virgula = ",";
     }

     if ($this->x38_nis !== null) {
       $sql  .= $virgula." x38_nis = '$this->x38_nis' ";
       $virgula = ",";
     }

     if($this->x38_emitiroutrosdebitos !== null) {
       $sql  .= $virgula." x38_emitiroutrosdebitos = '$this->x38_emitiroutrosdebitos' ";
       $virgula = ",";
     }

     if($this->x38_complemento !== null) {
       $sql  .= $virgula." x38_complemento = '$this->x38_complemento' ";
       $virgula = ",";
     }

     if($this->x38_observacoes !== null) { 
       $sql  .= $virgula." x38_observacoes = '$this->x38_observacoes' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x38_sequencial!=null){
       $sql .= " x38_sequencial = $this->x38_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x38_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22113,'$this->x38_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_sequencial"]) || $this->x38_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3983,22113,'".AddSlashes(pg_result($resaco,$conresaco,'x38_sequencial'))."','$this->x38_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_aguacontrato"]) || $this->x38_aguacontrato != "")
             $resac = db_query("insert into db_acount values($acount,3983,22114,'".AddSlashes(pg_result($resaco,$conresaco,'x38_aguacontrato'))."','$this->x38_aguacontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_cgm"]) || $this->x38_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3983,22115,'".AddSlashes(pg_result($resaco,$conresaco,'x38_cgm'))."','$this->x38_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_aguacategoriaconsumo"]) || $this->x38_aguacategoriaconsumo != "")
             $resac = db_query("insert into db_acount values($acount,3983,22116,'".AddSlashes(pg_result($resaco,$conresaco,'x38_aguacategoriaconsumo'))."','$this->x38_aguacategoriaconsumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_datavalidadecadastro"]) || $this->x38_datavalidadecadastro != "")
             $resac = db_query("insert into db_acount values($acount,3983,22117,'".AddSlashes(pg_result($resaco,$conresaco,'x38_datavalidadecadastro'))."','$this->x38_datavalidadecadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_nis"]) || $this->x38_nis != "")
             $resac = db_query("insert into db_acount values($acount,3983,22118,'".AddSlashes(pg_result($resaco,$conresaco,'x38_nis'))."','$this->x38_nis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_emitiroutrosdebitos"]) || $this->x38_emitiroutrosdebitos != "")
             $resac = db_query("insert into db_acount values($acount,3983,1009273,'".AddSlashes(pg_result($resaco,$conresaco,'x38_emitiroutrosdebitos'))."','$this->x38_emitiroutrosdebitos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_complemento"]) || $this->x38_complemento != "")
             $resac = db_query("insert into db_acount values($acount,3983,1009274,'".AddSlashes(pg_result($resaco,$conresaco,'x38_complemento'))."','$this->x38_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x38_observacoes"]) || $this->x38_observacoes != "")
             $resac = db_query("insert into db_acount values($acount,3983,1009393,'".AddSlashes(pg_result($resaco,$conresaco,'x38_observacoes'))."','$this->x38_observacoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Contrato Economia não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Agua Contrato Economia não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->x38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($x38_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($x38_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22113,'$x38_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3983,22113,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3983,22114,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_aguacontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3983,22115,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3983,22116,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_aguacategoriaconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3983,22117,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_datavalidadecadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3983,22118,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3983,1009273,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_emitiroutrosdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3983,1009274,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3983,1009393,'','".AddSlashes(pg_result($resaco,$iresaco,'x38_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from aguacontratoeconomia
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($x38_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " x38_sequencial = $x38_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Contrato Economia não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Agua Contrato Economia não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$x38_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacontratoeconomia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($x38_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "", $sGroupBy = null) {

     $sql  = "select {$campos}";
     $sql .= "  from aguacontratoeconomia ";
     $sql .= "      inner join cgm cgm_economia      on cgm_economia.z01_numcgm = aguacontratoeconomia.x38_cgm";
     $sql .= "      inner join aguacontrato  on  aguacontrato.x54_sequencial = aguacontratoeconomia.x38_aguacontrato";
     $sql .= "      inner join aguacategoriaconsumo  on  aguacategoriaconsumo.x13_sequencial = aguacontratoeconomia.x38_aguacategoriaconsumo";
     $sql .= "      inner join cgm cgm_contrato      on cgm_contrato.z01_numcgm = aguacontrato.x54_cgm";
     $sql .= "      left  join aguabase  on  aguabase.x01_matric = aguacontrato.x54_aguabase";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x38_sequencial)) {
         $sql2 .= " where aguacontratoeconomia.x38_sequencial = $x38_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;

     if ($sGroupBy) {
       $sql .= " group by {$sGroupBy} ";
     }

     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }

     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($x38_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "", $sGroupBy = null) {

     $sql  = "select {$campos} ";
     $sql .= "  from aguacontratoeconomia ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x38_sequencial)){
         $sql2 .= " where aguacontratoeconomia.x38_sequencial = $x38_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;

     if ($sGroupBy) {
      $sql .= " group by {$sGroupBy} ";
     }

     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }

     return $sql;
  }

}
