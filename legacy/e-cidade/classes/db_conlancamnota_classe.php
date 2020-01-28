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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamnota
class cl_conlancamnota {
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
    var $c66_codlan = 0;
    var $c66_codnota = 0;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 c66_codlan = int4 = Código Lançamento
                 c66_codnota = int4 = Nota
                 ";
    //funcao construtor da classe
    function cl_conlancamnota() {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("conlancamnota");
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
            $this->c66_codlan = ($this->c66_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c66_codlan"]:$this->c66_codlan);
            $this->c66_codnota = ($this->c66_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["c66_codnota"]:$this->c66_codnota);
        }else{
            $this->c66_codlan = ($this->c66_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c66_codlan"]:$this->c66_codlan);
            $this->c66_codnota = ($this->c66_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["c66_codnota"]:$this->c66_codnota);
        }
    }
    // funcao para inclusao
    function incluir ($c66_codlan,$c66_codnota){
        $this->atualizacampos();
        $this->c66_codlan = $c66_codlan;
        $this->c66_codnota = $c66_codnota;
        if(($this->c66_codlan == null) || ($this->c66_codlan == "") ){
            $this->erro_sql = " Campo c66_codlan nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if(($this->c66_codnota == null) || ($this->c66_codnota == "") ){
            $this->erro_sql = " Campo c66_codnota nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into conlancamnota(
                                       c66_codlan
                                      ,c66_codnota
                       )
                values (
                                $this->c66_codlan
                               ,$this->c66_codnota
                      )";
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
                $this->erro_sql   = "Notas do Lancamento ($this->c66_codlan."-".$this->c66_codnota) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = "Notas do Lancamento já Cadastrado";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }else{
                $this->erro_sql   = "Notas do Lancamento ($this->c66_codlan."-".$this->c66_codnota) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->c66_codlan."-".$this->c66_codnota;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        $resaco = $this->sql_record($this->sql_query_file($this->c66_codlan,$this->c66_codnota));
        if(($resaco!=false)||($this->numrows!=0)){
            $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
            $acount = pg_result($resac,0,0);
            $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
            $resac = db_query("insert into db_acountkey values($acount,6945,'$this->c66_codlan','I')");
            $resac = db_query("insert into db_acountkey values($acount,6946,'$this->c66_codnota','I')");
            $resac = db_query("insert into db_acount values($acount,1147,6945,'','".AddSlashes(pg_result($resaco,0,'c66_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            $resac = db_query("insert into db_acount values($acount,1147,6946,'','".AddSlashes(pg_result($resaco,0,'c66_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
        return true;
    }
    // funcao para alteracao
    function alterar ($c66_codlan=null,$c66_codnota=null) {
        $this->atualizacampos();
        $sql = " update conlancamnota set ";
        $virgula = "";
        if(trim($this->c66_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c66_codlan"])){
            $sql  .= $virgula." c66_codlan = $this->c66_codlan ";
            $virgula = ",";
            if(trim($this->c66_codlan) == null ){
                $this->erro_sql = " Campo Código Lançamento nao Informado.";
                $this->erro_campo = "c66_codlan";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->c66_codnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c66_codnota"])){
            $sql  .= $virgula." c66_codnota = $this->c66_codnota ";
            $virgula = ",";
            if(trim($this->c66_codnota) == null ){
                $this->erro_sql = " Campo Nota nao Informado.";
                $this->erro_campo = "c66_codnota";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if($c66_codlan!=null){
            $sql .= " c66_codlan = $this->c66_codlan";
        }
        if($c66_codnota!=null){
            $sql .= " and  c66_codnota = $this->c66_codnota";
        }
        $resaco = $this->sql_record($this->sql_query_file($this->c66_codlan,$this->c66_codnota));
        if($this->numrows>0){
            for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,6945,'$this->c66_codlan','A')");
                $resac = db_query("insert into db_acountkey values($acount,6946,'$this->c66_codnota','A')");
                if(isset($GLOBALS["HTTP_POST_VARS"]["c66_codlan"]))
                    $resac = db_query("insert into db_acount values($acount,1147,6945,'".AddSlashes(pg_result($resaco,$conresaco,'c66_codlan'))."','$this->c66_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                if(isset($GLOBALS["HTTP_POST_VARS"]["c66_codnota"]))
                    $resac = db_query("insert into db_acount values($acount,1147,6946,'".AddSlashes(pg_result($resaco,$conresaco,'c66_codnota'))."','$this->c66_codnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
        }
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Notas do Lancamento nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : ".$this->c66_codlan."-".$this->c66_codnota;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "Notas do Lancamento nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : ".$this->c66_codlan."-".$this->c66_codnota;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$this->c66_codlan."-".$this->c66_codnota;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir ($c66_codlan=null,$c66_codnota=null,$dbwhere=null) {
        if($dbwhere==null || $dbwhere==""){
            $resaco = $this->sql_record($this->sql_query_file($c66_codlan,$c66_codnota));
        }else{
            $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
        }
        if(($resaco!=false)||($this->numrows!=0)){
            for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,6945,'$c66_codlan','E')");
                $resac = db_query("insert into db_acountkey values($acount,6946,'$c66_codnota','E')");
                $resac = db_query("insert into db_acount values($acount,1147,6945,'','".AddSlashes(pg_result($resaco,$iresaco,'c66_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1147,6946,'','".AddSlashes(pg_result($resaco,$iresaco,'c66_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
        }
        $sql = " delete from conlancamnota
                    where ";
        $sql2 = "";
        if($dbwhere==null || $dbwhere ==""){
            if($c66_codlan != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " c66_codlan = $c66_codlan ";
            }
            if($c66_codnota != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " c66_codnota = $c66_codnota ";
            }
        }else{
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Notas do Lancamento nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$c66_codlan."-".$c66_codnota;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "Notas do Lancamento nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$c66_codlan."-".$c66_codnota;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$c66_codlan."-".$c66_codnota;
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
            $this->erro_sql   = "Record Vazio na Tabela:conlancamnota";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    function sql_query ( $c66_codlan=null,$c66_codnota=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from conlancamnota ";
        $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamnota.c66_codlan";
        $sql .= "      inner join empnota  on  empnota.e69_codnota = conlancamnota.c66_codnota";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
        $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
        $sql2 = "";
        if($dbwhere==""){
            if($c66_codlan!=null ){
                $sql2 .= " where conlancamnota.c66_codlan = $c66_codlan ";
            }
            if($c66_codnota!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " conlancamnota.c66_codnota = $c66_codnota ";
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

    /* Extensão CotaMensalLiquidacao */

    function sql_query_file ( $c66_codlan=null,$c66_codnota=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from conlancamnota ";
        $sql2 = "";
        if($dbwhere==""){
            if($c66_codlan!=null ){
                $sql2 .= " where conlancamnota.c66_codlan = $c66_codlan ";
            }
            if($c66_codnota!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " conlancamnota.c66_codnota = $c66_codnota ";
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

    function sql_query_contrato ( $c66_codlan=null,$c66_codnota=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from conlancamnota ";
        $sql .= "      inner join conlancamdoc on conlancamdoc.c71_codlan = conlancamnota.c66_codlan";
        $sql2 = "";
        if($dbwhere==""){
            if($c66_codlan!=null ){
                $sql2 .= " where conlancamnota.c66_codlan = $c66_codlan ";
            }
            if($c66_codnota!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " conlancamnota.c66_codnota = $c66_codnota ";
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

    /**
     * @param array $aCampos
     * @param array $aWhere
     * @param array $aOrder
     * @return string
     */
    public function sqlProgramacaoFinanceiraParcela($aCampos = array(), $aWhere = array(), $aOrder = array()) {

        $sCampos = count($aCampos) > 0 ? implode(', ', $aCampos)   : '*';
        $sWhere  = count($aWhere) > 0  ? implode(' AND ', $aWhere) : '';
        $sOrder  = count($aOrder) > 0  ? implode(', ', $aOrder)    : '';

        $sSql  = "select {$sCampos}";
        $sSql .= "  from conlancamnota";
        $sSql .= "       inner join conlancamprogramacaofinanceiraparcela on c118_conlancam  = c66_codlan";
        $sSql .= "       inner join programacaofinanceiraparcela          on k118_sequencial = c118_programacaofinanceiraparcela";
        $sSql .= "  where {$sWhere}";
        $sSql .= "  order by {$sOrder}";

        return $sSql;
    }
}
