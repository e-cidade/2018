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

//MODULO: Escola
//CLASSE DA ENTIDADE censoetapa
class cl_censoetapa {
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
   var $ed266_i_codigo = 0;
   var $ed266_c_descr = null;
   var $ed266_c_regular = null;
   var $ed266_c_especial = null;
   var $ed266_c_eja = null;
   var $ed266_ano = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed266_i_codigo = int4 = Código
                 ed266_c_descr = char(100) = Descrição
                 ed266_c_regular = char(1) = Regular
                 ed266_c_especial = char(1) = Especial
                 ed266_c_eja = char(1) = EJA
                 ed266_ano = int4 = Ano
                 ";
   //funcao construtor da classe
   function cl_censoetapa() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("censoetapa");
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
       $this->ed266_i_codigo = ($this->ed266_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed266_i_codigo"]:$this->ed266_i_codigo);
       $this->ed266_c_descr = ($this->ed266_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed266_c_descr"]:$this->ed266_c_descr);
       $this->ed266_c_regular = ($this->ed266_c_regular == ""?@$GLOBALS["HTTP_POST_VARS"]["ed266_c_regular"]:$this->ed266_c_regular);
       $this->ed266_c_especial = ($this->ed266_c_especial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed266_c_especial"]:$this->ed266_c_especial);
       $this->ed266_c_eja = ($this->ed266_c_eja == ""?@$GLOBALS["HTTP_POST_VARS"]["ed266_c_eja"]:$this->ed266_c_eja);
       $this->ed266_ano = ($this->ed266_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed266_ano"]:$this->ed266_ano);
     }else{
       $this->ed266_i_codigo = ($this->ed266_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed266_i_codigo"]:$this->ed266_i_codigo);
       $this->ed266_ano = ($this->ed266_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed266_ano"]:$this->ed266_ano);
     }
   }
   // funcao para Inclusão
   function incluir ($ed266_i_codigo,$ed266_ano){
      $this->atualizacampos();
     if($this->ed266_c_descr == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "ed266_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed266_c_regular == null ){
       $this->ed266_c_regular = "";
     }
     if($this->ed266_c_especial == null ){
       $this->ed266_c_especial = "";
     }
     if($this->ed266_c_eja == null ){
       $this->ed266_c_eja = "";
     }
       $this->ed266_i_codigo = $ed266_i_codigo;
       $this->ed266_ano = $ed266_ano;
     if(($this->ed266_i_codigo == null) || ($this->ed266_i_codigo == "") ){
       $this->erro_sql = " Campo ed266_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->ed266_ano == null) || ($this->ed266_ano == "") ){
       $this->erro_sql = " Campo ed266_ano não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into censoetapa(
                                       ed266_i_codigo
                                      ,ed266_c_descr
                                      ,ed266_c_regular
                                      ,ed266_c_especial
                                      ,ed266_c_eja
                                      ,ed266_ano
                       )
                values (
                                $this->ed266_i_codigo
                               ,'$this->ed266_c_descr'
                               ,'$this->ed266_c_regular'
                               ,'$this->ed266_c_especial'
                               ,'$this->ed266_c_eja'
                               ,$this->ed266_ano
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Etapas/Séries do Censo Escolar ($this->ed266_i_codigo."-".$this->ed266_ano) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Etapas/Séries do Censo Escolar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Etapas/Séries do Censo Escolar ($this->ed266_i_codigo."-".$this->ed266_ano) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed266_i_codigo."-".$this->ed266_ano;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed266_i_codigo,$this->ed266_ano  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13780,'$this->ed266_i_codigo','I')");
         $resac = db_query("insert into db_acountkey values($acount,21044,'$this->ed266_ano','I')");
         $resac = db_query("insert into db_acount values($acount,2413,13780,'','".AddSlashes(pg_result($resaco,0,'ed266_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2413,13781,'','".AddSlashes(pg_result($resaco,0,'ed266_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2413,13782,'','".AddSlashes(pg_result($resaco,0,'ed266_c_regular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2413,13783,'','".AddSlashes(pg_result($resaco,0,'ed266_c_especial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2413,13784,'','".AddSlashes(pg_result($resaco,0,'ed266_c_eja'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2413,21044,'','".AddSlashes(pg_result($resaco,0,'ed266_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed266_i_codigo=null,$ed266_ano=null) {
      $this->atualizacampos();
     $sql = " update censoetapa set ";
     $virgula = "";
     if(trim($this->ed266_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed266_i_codigo"])){
       $sql  .= $virgula." ed266_i_codigo = $this->ed266_i_codigo ";
       $virgula = ",";
       if(trim($this->ed266_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed266_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed266_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed266_c_descr"])){
       $sql  .= $virgula." ed266_c_descr = '$this->ed266_c_descr' ";
       $virgula = ",";
       if(trim($this->ed266_c_descr) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "ed266_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed266_c_regular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed266_c_regular"])){
       $sql  .= $virgula." ed266_c_regular = '$this->ed266_c_regular' ";
       $virgula = ",";
     }
     if(trim($this->ed266_c_especial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed266_c_especial"])){
       $sql  .= $virgula." ed266_c_especial = '$this->ed266_c_especial' ";
       $virgula = ",";
     }
     if(trim($this->ed266_c_eja)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed266_c_eja"])){
       $sql  .= $virgula." ed266_c_eja = '$this->ed266_c_eja' ";
       $virgula = ",";
     }
     if(trim($this->ed266_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed266_ano"])){
       $sql  .= $virgula." ed266_ano = $this->ed266_ano ";
       $virgula = ",";
       if(trim($this->ed266_ano) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed266_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed266_i_codigo!=null){
       $sql .= " ed266_i_codigo = $this->ed266_i_codigo";
     }
     if($ed266_ano!=null){
       $sql .= " and  ed266_ano = $this->ed266_ano";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed266_i_codigo,$this->ed266_ano));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,13780,'$this->ed266_i_codigo','A')");
           $resac = db_query("insert into db_acountkey values($acount,21044,'$this->ed266_ano','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed266_i_codigo"]) || $this->ed266_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2413,13780,'".AddSlashes(pg_result($resaco,$conresaco,'ed266_i_codigo'))."','$this->ed266_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed266_c_descr"]) || $this->ed266_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,2413,13781,'".AddSlashes(pg_result($resaco,$conresaco,'ed266_c_descr'))."','$this->ed266_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed266_c_regular"]) || $this->ed266_c_regular != "")
             $resac = db_query("insert into db_acount values($acount,2413,13782,'".AddSlashes(pg_result($resaco,$conresaco,'ed266_c_regular'))."','$this->ed266_c_regular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed266_c_especial"]) || $this->ed266_c_especial != "")
             $resac = db_query("insert into db_acount values($acount,2413,13783,'".AddSlashes(pg_result($resaco,$conresaco,'ed266_c_especial'))."','$this->ed266_c_especial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed266_c_eja"]) || $this->ed266_c_eja != "")
             $resac = db_query("insert into db_acount values($acount,2413,13784,'".AddSlashes(pg_result($resaco,$conresaco,'ed266_c_eja'))."','$this->ed266_c_eja',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed266_ano"]) || $this->ed266_ano != "")
             $resac = db_query("insert into db_acount values($acount,2413,21044,'".AddSlashes(pg_result($resaco,$conresaco,'ed266_ano'))."','$this->ed266_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Etapas/Séries do Censo Escolar não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed266_i_codigo."-".$this->ed266_ano;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Etapas/Séries do Censo Escolar não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed266_i_codigo."-".$this->ed266_ano;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed266_i_codigo."-".$this->ed266_ano;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed266_i_codigo=null,$ed266_ano=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed266_i_codigo,$ed266_ano));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,13780,'$ed266_i_codigo','E')");
           $resac  = db_query("insert into db_acountkey values($acount,21044,'$ed266_ano','E')");
           $resac  = db_query("insert into db_acount values($acount,2413,13780,'','".AddSlashes(pg_result($resaco,$iresaco,'ed266_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2413,13781,'','".AddSlashes(pg_result($resaco,$iresaco,'ed266_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2413,13782,'','".AddSlashes(pg_result($resaco,$iresaco,'ed266_c_regular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2413,13783,'','".AddSlashes(pg_result($resaco,$iresaco,'ed266_c_especial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2413,13784,'','".AddSlashes(pg_result($resaco,$iresaco,'ed266_c_eja'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2413,21044,'','".AddSlashes(pg_result($resaco,$iresaco,'ed266_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from censoetapa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed266_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed266_i_codigo = $ed266_i_codigo ";
        }
        if (!empty($ed266_ano)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed266_ano = $ed266_ano ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Etapas/Séries do Censo Escolar não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed266_i_codigo."-".$ed266_ano;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Etapas/Séries do Censo Escolar não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed266_i_codigo."-".$ed266_ano;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed266_i_codigo."-".$ed266_ano;
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
        $this->erro_sql   = "Record Vazio na Tabela:censoetapa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed266_i_codigo = null,$ed266_ano = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from censoetapa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed266_i_codigo)) {
         $sql2 .= " where censoetapa.ed266_i_codigo = $ed266_i_codigo ";
       }
       if (!empty($ed266_ano)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " censoetapa.ed266_ano = $ed266_ano ";
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
   public function sql_query_file ($ed266_i_codigo = null,$ed266_ano = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from censoetapa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed266_i_codigo)){
         $sql2 .= " where censoetapa.ed266_i_codigo = $ed266_i_codigo ";
       }
       if (!empty($ed266_ano)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " censoetapa.ed266_ano = $ed266_ano ";
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

  public function sql_query_mediacao ($ed266_i_codigo = null,$ed266_ano = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from censoetapa ";
     $sql .= "  inner join censoetapamediacaodidaticopedagogica on ( censoetapamediacaodidaticopedagogica.ed131_censoetapa = censoetapa.ed266_i_codigo";
     $sql .= "                                                      and censoetapamediacaodidaticopedagogica.ed131_ano    = censoetapa.ed266_ano";
     $sql .= "                                                     )";
     $sql .= "  inner join ensino on ensino.ed10_mediacaodidaticopedagogica = censoetapamediacaodidaticopedagogica.ed131_mediacaodidaticopedagogica";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed266_i_codigo)) {
         $sql2 .= " where censoetapa.ed266_i_codigo = $ed266_i_codigo ";
       }
       if (!empty($ed266_ano)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " censoetapa.ed266_ano = $ed266_ano ";
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
