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
//MODULO: arrecadacao
//CLASSE DA ENTIDADE taxa
class cl_taxa {
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
    var $ar36_sequencial = 0;
    var $ar36_grupotaxa = 0;
    var $ar36_receita = 0;
    var $ar36_descricao = null;
    var $ar36_perc = 0;
    var $ar36_valor = 0;
    var $ar36_valormin = 0;
    var $ar36_valormax = 0;
    var $ar36_debitoscomprocesso = 'f';
    var $ar36_debitossemprocesso = 'f';
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 ar36_sequencial = int4 = Sequencial 
                 ar36_grupotaxa = int4 = Grupo de Tarifas 
                 ar36_receita = int4 = Receita 
                 ar36_descricao = varchar(150) = Descrição 
                 ar36_perc = float8 = Percentual 
                 ar36_valor = float8 = Valor 
                 ar36_valormin = float8 = Valor Minimo 
                 ar36_valormax = float8 = Valor Maximo 
                 ar36_debitoscomprocesso = bool = Débitos Com Processo 
                 ar36_debitossemprocesso = bool = Débitos Sem Processo 
                 ";
    //funcao construtor da classe
    function cl_taxa() {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("taxa");
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
            $this->ar36_sequencial = (empty($this->ar36_sequencial) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_sequencial"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_sequencial"]:$this->ar36_sequencial);
            $this->ar36_grupotaxa = (empty($this->ar36_grupotaxa) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_grupotaxa"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_grupotaxa"] : $this->ar36_grupotaxa);
            $this->ar36_receita = (empty($this->ar36_receita) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_receita"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_receita"] : $this->ar36_receita);
            $this->ar36_descricao = (empty($this->ar36_descricao) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_descricao"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_descricao"] : $this->ar36_descricao);
            $this->ar36_perc = (empty($this->ar36_perc) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_perc"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_perc"] : $this->ar36_perc);
            $this->ar36_valor = (empty($this->ar36_valor) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_valor"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_valor"] : $this->ar36_valor);
            $this->ar36_valormin = (empty($this->ar36_valormin) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_valormin"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_valormin"] : $this->ar36_valormin);
            $this->ar36_valormax = (empty($this->ar36_valormax) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_valormax"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_valormax"] : $this->ar36_valormax);
            $this->ar36_debitoscomprocesso = (empty($this->ar36_debitoscomprocesso) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_debitoscomprocesso"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_debitoscomprocesso"] : $this->ar36_debitoscomprocesso);
            $this->ar36_debitossemprocesso = (empty($this->ar36_debitossemprocesso) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_debitossemprocesso"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_debitossemprocesso"] : $this->ar36_debitossemprocesso);
        }else{
            $this->ar36_sequencial = (empty($this->ar36_sequencial) && isset($GLOBALS["HTTP_POST_VARS"]["ar36_sequencial"]) ? $GLOBALS["HTTP_POST_VARS"]["ar36_sequencial"] : $this->ar36_sequencial);
        }
    }
    // funcao para Inclusao
    function incluir ($ar36_sequencial){
        $this->atualizacampos();
        if($this->ar36_grupotaxa == null ){
            $this->erro_sql = " Campo Grupo de Tarifas não informado.";
            $this->erro_campo = "ar36_grupotaxa";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->ar36_receita == null ){
            $this->erro_sql = " Campo Receita não informado.";
            $this->erro_campo = "ar36_receita";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->ar36_descricao == null ){
            $this->erro_sql = " Campo Descrição não informado.";
            $this->erro_campo = "ar36_descricao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->ar36_perc == null ){
            $this->ar36_perc = "0";
        }
        if($this->ar36_valor == null ){
            $this->ar36_valor = "0";
        }
        if($this->ar36_valormin == null ){
            $this->ar36_valormin = "0";
        }
        if($this->ar36_valormax == null ){
            $this->ar36_valormax = "0";
        }
        if($this->ar36_debitoscomprocesso == null ){
            $this->erro_sql = " Campo Débitos Com Processo não informado.";
            $this->erro_campo = "ar36_debitoscomprocesso";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->ar36_debitossemprocesso == null ){
            $this->erro_sql = " Campo Débitos Sem Processo não informado.";
            $this->erro_campo = "ar36_debitossemprocesso";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($ar36_sequencial == "" || $ar36_sequencial == null ){
            $result = db_query("select nextval('taxa_ar36_sequencial_seq')");
            if($result==false){
                $this->erro_banco = str_replace("\n","",pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: taxa_ar36_sequencial_seq do campo: ar36_sequencial";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ar36_sequencial = pg_result($result,0,0);
        }else{
            $result = db_query("select last_value from taxa_ar36_sequencial_seq");
            if(($result != false) && (pg_result($result,0,0) < $ar36_sequencial)){
                $this->erro_sql = " Campo ar36_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }else{
                $this->ar36_sequencial = $ar36_sequencial;
            }
        }
        if(($this->ar36_sequencial == null) || ($this->ar36_sequencial == "") ){
            $this->erro_sql = " Campo ar36_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into taxa(
                                       ar36_sequencial 
                                      ,ar36_grupotaxa 
                                      ,ar36_receita 
                                      ,ar36_descricao 
                                      ,ar36_perc 
                                      ,ar36_valor 
                                      ,ar36_valormin 
                                      ,ar36_valormax 
                                      ,ar36_debitoscomprocesso 
                                      ,ar36_debitossemprocesso 
                       )
                values (
                                $this->ar36_sequencial 
                               ,$this->ar36_grupotaxa 
                               ,$this->ar36_receita 
                               ,'$this->ar36_descricao' 
                               ,$this->ar36_perc 
                               ,$this->ar36_valor 
                               ,$this->ar36_valormin 
                               ,$this->ar36_valormax 
                               ,'$this->ar36_debitoscomprocesso' 
                               ,'$this->ar36_debitossemprocesso' 
                      )";
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","", pg_last_error());
            if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
                $this->erro_sql   = "Taxas ($this->ar36_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = "Taxas já Cadastrado";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }else{
                $this->erro_sql   = "Taxas ($this->ar36_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com Sucesso.\\n";
        $this->erro_sql .= "Valores : ".$this->ar36_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ar36_sequencial  ));
            if(($resaco!=false)||($this->numrows!=0)){

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,18215,'$this->ar36_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,3221,18215,'','".AddSlashes(pg_result($resaco,0,'ar36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,18216,'','".AddSlashes(pg_result($resaco,0,'ar36_grupotaxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,18272,'','".AddSlashes(pg_result($resaco,0,'ar36_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,18217,'','".AddSlashes(pg_result($resaco,0,'ar36_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,18218,'','".AddSlashes(pg_result($resaco,0,'ar36_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,18219,'','".AddSlashes(pg_result($resaco,0,'ar36_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,18220,'','".AddSlashes(pg_result($resaco,0,'ar36_valormin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,18221,'','".AddSlashes(pg_result($resaco,0,'ar36_valormax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,1009487,'','".AddSlashes(pg_result($resaco,0,'ar36_debitoscomprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,3221,1009488,'','".AddSlashes(pg_result($resaco,0,'ar36_debitossemprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
        }
        return true;
    }
    // funcao para alteracao
    public function alterar ($ar36_sequencial=null) {
        $this->atualizacampos();
        $sql = " update taxa set ";
        $virgula = "";
        if(trim($this->ar36_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_sequencial"])){
            $sql  .= $virgula." ar36_sequencial = $this->ar36_sequencial ";
            $virgula = ",";
            if(trim($this->ar36_sequencial) == null ){
                $this->erro_sql = " Campo Sequencial não informado.";
                $this->erro_campo = "ar36_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->ar36_grupotaxa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_grupotaxa"])){
            $sql  .= $virgula." ar36_grupotaxa = $this->ar36_grupotaxa ";
            $virgula = ",";
            if(trim($this->ar36_grupotaxa) == null ){
                $this->erro_sql = " Campo Grupo de Tarifas não informado.";
                $this->erro_campo = "ar36_grupotaxa";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->ar36_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_receita"])){
            $sql  .= $virgula." ar36_receita = $this->ar36_receita ";
            $virgula = ",";
            if(trim($this->ar36_receita) == null ){
                $this->erro_sql = " Campo Receita não informado.";
                $this->erro_campo = "ar36_receita";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->ar36_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_descricao"])){
            $sql  .= $virgula." ar36_descricao = '$this->ar36_descricao' ";
            $virgula = ",";
            if(trim($this->ar36_descricao) == null ){
                $this->erro_sql = " Campo Descrição não informado.";
                $this->erro_campo = "ar36_descricao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->ar36_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_perc"])){
            if(trim($this->ar36_perc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ar36_perc"])){
                $this->ar36_perc = "0" ;
            }
            $sql  .= $virgula." ar36_perc = $this->ar36_perc ";
            $virgula = ",";
        }
        if(trim($this->ar36_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_valor"])){
            if(trim($this->ar36_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ar36_valor"])){
                $this->ar36_valor = "0" ;
            }
            $sql  .= $virgula." ar36_valor = $this->ar36_valor ";
            $virgula = ",";
        }
        if(trim($this->ar36_valormin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_valormin"])){
            if(trim($this->ar36_valormin)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ar36_valormin"])){
                $this->ar36_valormin = "0" ;
            }
            $sql  .= $virgula." ar36_valormin = $this->ar36_valormin ";
            $virgula = ",";
        }
        if(trim($this->ar36_valormax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_valormax"])){
            if(trim($this->ar36_valormax)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ar36_valormax"])){
                $this->ar36_valormax = "0" ;
            }
            $sql  .= $virgula." ar36_valormax = $this->ar36_valormax ";
            $virgula = ",";
        }
        if(trim($this->ar36_debitoscomprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_debitoscomprocesso"])){
            $sql  .= $virgula." ar36_debitoscomprocesso = '$this->ar36_debitoscomprocesso' ";
            $virgula = ",";
            if(trim($this->ar36_debitoscomprocesso) == null ){
                $this->erro_sql = " Campo Débitos Com Processo não informado.";
                $this->erro_campo = "ar36_debitoscomprocesso";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->ar36_debitossemprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar36_debitossemprocesso"])){
            $sql  .= $virgula." ar36_debitossemprocesso = '$this->ar36_debitossemprocesso' ";
            $virgula = ",";
            if(trim($this->ar36_debitossemprocesso) == null ){
                $this->erro_sql = " Campo Débitos Sem Processo não informado.";
                $this->erro_campo = "ar36_debitossemprocesso";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if($ar36_sequencial!=null){
            $sql .= " ar36_sequencial = $this->ar36_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ar36_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac = db_query("insert into db_acountkey values($acount,18215,'$this->ar36_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_sequencial"]) || $this->ar36_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,3221,18215,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_sequencial'))."','$this->ar36_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_grupotaxa"]) || $this->ar36_grupotaxa != "")
                        $resac = db_query("insert into db_acount values($acount,3221,18216,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_grupotaxa'))."','$this->ar36_grupotaxa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_receita"]) || $this->ar36_receita != "")
                        $resac = db_query("insert into db_acount values($acount,3221,18272,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_receita'))."','$this->ar36_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_descricao"]) || $this->ar36_descricao != "")
                        $resac = db_query("insert into db_acount values($acount,3221,18217,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_descricao'))."','$this->ar36_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_perc"]) || $this->ar36_perc != "")
                        $resac = db_query("insert into db_acount values($acount,3221,18218,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_perc'))."','$this->ar36_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_valor"]) || $this->ar36_valor != "")
                        $resac = db_query("insert into db_acount values($acount,3221,18219,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_valor'))."','$this->ar36_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_valormin"]) || $this->ar36_valormin != "")
                        $resac = db_query("insert into db_acount values($acount,3221,18220,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_valormin'))."','$this->ar36_valormin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_valormax"]) || $this->ar36_valormax != "")
                        $resac = db_query("insert into db_acount values($acount,3221,18221,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_valormax'))."','$this->ar36_valormax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_debitoscomprocesso"]) || $this->ar36_debitoscomprocesso != "")
                        $resac = db_query("insert into db_acount values($acount,3221,1009487,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_debitoscomprocesso'))."','$this->ar36_debitoscomprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ar36_debitossemprocesso"]) || $this->ar36_debitossemprocesso != "")
                        $resac = db_query("insert into db_acount values($acount,3221,1009488,'".AddSlashes(pg_result($resaco,$conresaco,'ar36_debitossemprocesso'))."','$this->ar36_debitossemprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n","",pg_last_error());
            $this->erro_sql   = "Taxas não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : ".$this->ar36_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Taxas não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : ".$this->ar36_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso.\\n";
                $this->erro_sql .= "Valores : ".$this->ar36_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    public function excluir ($ar36_sequencial=null,$dbwhere=null) {

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ar36_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
            }
            if (($resaco != false) || ($this->numrows!=0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac  = db_query("insert into db_acountkey values($acount,18215,'$ar36_sequencial','E')");
                    $resac  = db_query("insert into db_acount values($acount,3221,18215,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,18216,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_grupotaxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,18272,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,18217,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,18218,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,18219,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,18220,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_valormin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,18221,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_valormax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,1009487,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_debitoscomprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3221,1009488,'','".AddSlashes(pg_result($resaco,$iresaco,'ar36_debitossemprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
            }
        }
        $sql = " delete from taxa
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ar36_sequencial)){
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ar36_sequencial = $ar36_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n","",pg_last_error());
            $this->erro_sql   = "Taxas não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$ar36_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Taxas não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$ar36_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso.\\n";
                $this->erro_sql .= "Valores : ".$ar36_sequencial;
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
            $this->erro_banco = str_replace("\n","",pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:taxa";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql
    public function sql_query ($ar36_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

        $sql  = "select {$campos}";
        $sql .= "  from taxa ";
        $sql .= "      inner join tabrec  on  tabrec.k02_codigo = taxa.ar36_receita";
        $sql .= "      inner join grupotaxa  on  grupotaxa.ar37_sequencial = taxa.ar36_grupotaxa";
        $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
        $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
        $sql .= "      inner join grupotaxatipo  on  grupotaxatipo.ar38_sequencial = grupotaxa.ar37_grupotaxatipo";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ar36_sequencial)) {
                $sql2 .= " where taxa.ar36_sequencial = $ar36_sequencial ";
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
    public function sql_query_file ($ar36_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

        $sql  = "select {$campos} ";
        $sql .= "  from taxa ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ar36_sequencial)){
                $sql2 .= " where taxa.ar36_sequencial = $ar36_sequencial ";
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
