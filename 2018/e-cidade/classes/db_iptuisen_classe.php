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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptuisen
class cl_iptuisen {
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
   var $j46_codigo = 0;
   var $j46_matric = 0;
   var $j46_tipo = 0;
   var $j46_dtini_dia = null;
   var $j46_dtini_mes = null;
   var $j46_dtini_ano = null;
   var $j46_dtini = null;
   var $j46_dtfim_dia = null;
   var $j46_dtfim_mes = null;
   var $j46_dtfim_ano = null;
   var $j46_dtfim = null;
   var $j46_perc = 0;
   var $j46_dtinc_dia = null;
   var $j46_dtinc_mes = null;
   var $j46_dtinc_ano = null;
   var $j46_dtinc = null;
   var $j46_idusu = 0;
   var $j46_hist = null;
   var $j46_arealo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 j46_codigo = int4 = Codigo Isencao
                 j46_matric = int4 = Matrícula
                 j46_tipo = int4 = Tipo Isencao
                 j46_dtini = date = Data Inicio
                 j46_dtfim = date = Data Final
                 j46_perc = float8 = Percentual
                 j46_dtinc = date = Data inclusao
                 j46_idusu = int4 = Codigo do Usuario
                 j46_hist = text = Historico
                 j46_arealo = float8 = Área do lote M2
                 ";
   //funcao construtor da classe
   function cl_iptuisen() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptuisen");
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
       $this->j46_codigo = ($this->j46_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_codigo"]:$this->j46_codigo);
       $this->j46_matric = ($this->j46_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_matric"]:$this->j46_matric);
       $this->j46_tipo = ($this->j46_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_tipo"]:$this->j46_tipo);
       if($this->j46_dtini == ""){
         $this->j46_dtini_dia = ($this->j46_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtini_dia"]:$this->j46_dtini_dia);
         $this->j46_dtini_mes = ($this->j46_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtini_mes"]:$this->j46_dtini_mes);
         $this->j46_dtini_ano = ($this->j46_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtini_ano"]:$this->j46_dtini_ano);
         if($this->j46_dtini_dia != ""){
            $this->j46_dtini = $this->j46_dtini_ano."-".$this->j46_dtini_mes."-".$this->j46_dtini_dia;
         }
       }
       if($this->j46_dtfim == ""){
         $this->j46_dtfim_dia = ($this->j46_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtfim_dia"]:$this->j46_dtfim_dia);
         $this->j46_dtfim_mes = ($this->j46_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtfim_mes"]:$this->j46_dtfim_mes);
         $this->j46_dtfim_ano = ($this->j46_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtfim_ano"]:$this->j46_dtfim_ano);
         if($this->j46_dtfim_dia != ""){
            $this->j46_dtfim = $this->j46_dtfim_ano."-".$this->j46_dtfim_mes."-".$this->j46_dtfim_dia;
         }
       }
       $this->j46_perc = ($this->j46_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_perc"]:$this->j46_perc);
       if($this->j46_dtinc == ""){
         $this->j46_dtinc_dia = ($this->j46_dtinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtinc_dia"]:$this->j46_dtinc_dia);
         $this->j46_dtinc_mes = ($this->j46_dtinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtinc_mes"]:$this->j46_dtinc_mes);
         $this->j46_dtinc_ano = ($this->j46_dtinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_dtinc_ano"]:$this->j46_dtinc_ano);
         if($this->j46_dtinc_dia != ""){
            $this->j46_dtinc = $this->j46_dtinc_ano."-".$this->j46_dtinc_mes."-".$this->j46_dtinc_dia;
         }
       }
       $this->j46_idusu = ($this->j46_idusu == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_idusu"]:$this->j46_idusu);
       $this->j46_hist = ($this->j46_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_hist"]:$this->j46_hist);
       $this->j46_arealo = ($this->j46_arealo == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_arealo"]:$this->j46_arealo);
     }else{
       $this->j46_codigo = ($this->j46_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j46_codigo"]:$this->j46_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j46_codigo){
      $this->atualizacampos();
     if($this->j46_matric == null ){
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "j46_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j46_tipo == null ){
       $this->erro_sql = " Campo Tipo Isencao nao Informado.";
       $this->erro_campo = "j46_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j46_dtini == null ){
       $this->erro_sql = " Campo Data Inicio nao Informado.";
       $this->erro_campo = "j46_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j46_dtfim == null ){
       $this->j46_dtfim = "null";
     }
     if($this->j46_perc == null ){
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "j46_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j46_dtinc == null ){
       $this->erro_sql = " Campo Data inclusao nao Informado.";
       $this->erro_campo = "j46_dtinc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j46_idusu == null ){
       $this->erro_sql = " Campo Codigo do Usuario nao Informado.";
       $this->erro_campo = "j46_idusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j46_hist == null ){
       $this->erro_sql = " Campo Historico nao Informado.";
       $this->erro_campo = "j46_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j46_arealo == null ){
       $this->j46_arealo = "0";
     }
     if($j46_codigo == "" || $j46_codigo == null ){
       $result = db_query("select nextval('iptuisen_j46_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptuisen_j46_codigo_seq do campo: j46_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->j46_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from iptuisen_j46_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j46_codigo)){
         $this->erro_sql = " Campo j46_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j46_codigo = $j46_codigo;
       }
     }
     if(($this->j46_codigo == null) || ($this->j46_codigo == "") ){
       $this->erro_sql = " Campo j46_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptuisen(
                                       j46_codigo
                                      ,j46_matric
                                      ,j46_tipo
                                      ,j46_dtini
                                      ,j46_dtfim
                                      ,j46_perc
                                      ,j46_dtinc
                                      ,j46_idusu
                                      ,j46_hist
                                      ,j46_arealo
                       )
                values (
                                $this->j46_codigo
                               ,$this->j46_matric
                               ,$this->j46_tipo
                               ,".($this->j46_dtini == "null" || $this->j46_dtini == ""?"null":"'".$this->j46_dtini."'")."
                               ,".($this->j46_dtfim == "null" || $this->j46_dtfim == ""?"null":"'".$this->j46_dtfim."'")."
                               ,$this->j46_perc
                               ,".($this->j46_dtinc == "null" || $this->j46_dtinc == ""?"null":"'".$this->j46_dtinc."'")."
                               ,$this->j46_idusu
                               ,'$this->j46_hist'
                               ,$this->j46_arealo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j46_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j46_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j46_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j46_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,187,'$this->j46_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,38,187,'','".AddSlashes(pg_result($resaco,0,'j46_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,188,'','".AddSlashes(pg_result($resaco,0,'j46_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,189,'','".AddSlashes(pg_result($resaco,0,'j46_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,190,'','".AddSlashes(pg_result($resaco,0,'j46_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,191,'','".AddSlashes(pg_result($resaco,0,'j46_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,192,'','".AddSlashes(pg_result($resaco,0,'j46_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,193,'','".AddSlashes(pg_result($resaco,0,'j46_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,194,'','".AddSlashes(pg_result($resaco,0,'j46_idusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,195,'','".AddSlashes(pg_result($resaco,0,'j46_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,38,5908,'','".AddSlashes(pg_result($resaco,0,'j46_arealo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($j46_codigo=null) {
      $this->atualizacampos();
     $sql = " update iptuisen set ";
     $virgula = "";
     if(trim($this->j46_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_codigo"])){
       $sql  .= $virgula." j46_codigo = $this->j46_codigo ";
       $virgula = ",";
       if(trim($this->j46_codigo) == null ){
         $this->erro_sql = " Campo Codigo Isencao nao Informado.";
         $this->erro_campo = "j46_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j46_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_matric"])){
       $sql  .= $virgula." j46_matric = $this->j46_matric ";
       $virgula = ",";
       if(trim($this->j46_matric) == null ){
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "j46_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j46_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_tipo"])){
       $sql  .= $virgula." j46_tipo = $this->j46_tipo ";
       $virgula = ",";
       if(trim($this->j46_tipo) == null ){
         $this->erro_sql = " Campo Tipo Isencao nao Informado.";
         $this->erro_campo = "j46_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j46_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j46_dtini_dia"] !="") ){
       $sql  .= $virgula." j46_dtini = '$this->j46_dtini' ";
       $virgula = ",";
       if(trim($this->j46_dtini) == null ){
         $this->erro_sql = " Campo Data Inicio nao Informado.";
         $this->erro_campo = "j46_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["j46_dtini_dia"])){
         $sql  .= $virgula." j46_dtini = null ";
         $virgula = ",";
         if(trim($this->j46_dtini) == null ){
           $this->erro_sql = " Campo Data Inicio nao Informado.";
           $this->erro_campo = "j46_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j46_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j46_dtfim_dia"] !="") ){
       $sql  .= $virgula." j46_dtfim = '$this->j46_dtfim' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["j46_dtfim_dia"])){
         $sql  .= $virgula." j46_dtfim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->j46_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_perc"])){
       $sql  .= $virgula." j46_perc = $this->j46_perc ";
       $virgula = ",";
       if(trim($this->j46_perc) == null ){
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "j46_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j46_dtinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_dtinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j46_dtinc_dia"] !="") ){
       $sql  .= $virgula." j46_dtinc = '$this->j46_dtinc' ";
       $virgula = ",";
       if(trim($this->j46_dtinc) == null ){
         $this->erro_sql = " Campo Data inclusao nao Informado.";
         $this->erro_campo = "j46_dtinc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["j46_dtinc_dia"])){
         $sql  .= $virgula." j46_dtinc = null ";
         $virgula = ",";
         if(trim($this->j46_dtinc) == null ){
           $this->erro_sql = " Campo Data inclusao nao Informado.";
           $this->erro_campo = "j46_dtinc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j46_idusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_idusu"])){
       $sql  .= $virgula." j46_idusu = $this->j46_idusu ";
       $virgula = ",";
       if(trim($this->j46_idusu) == null ){
         $this->erro_sql = " Campo Codigo do Usuario nao Informado.";
         $this->erro_campo = "j46_idusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j46_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_hist"])){
       $sql  .= $virgula." j46_hist = '$this->j46_hist' ";
       $virgula = ",";
       if(trim($this->j46_hist) == null ){
         $this->erro_sql = " Campo Historico nao Informado.";
         $this->erro_campo = "j46_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j46_arealo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j46_arealo"])){
        if(trim($this->j46_arealo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j46_arealo"])){
           $this->j46_arealo = "0" ;
        }
       $sql  .= $virgula." j46_arealo = $this->j46_arealo ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j46_codigo!=null){
       $sql .= " j46_codigo = $this->j46_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j46_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,187,'$this->j46_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_codigo"]))
           $resac = db_query("insert into db_acount values($acount,38,187,'".AddSlashes(pg_result($resaco,$conresaco,'j46_codigo'))."','$this->j46_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_matric"]))
           $resac = db_query("insert into db_acount values($acount,38,188,'".AddSlashes(pg_result($resaco,$conresaco,'j46_matric'))."','$this->j46_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_tipo"]))
           $resac = db_query("insert into db_acount values($acount,38,189,'".AddSlashes(pg_result($resaco,$conresaco,'j46_tipo'))."','$this->j46_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_dtini"]))
           $resac = db_query("insert into db_acount values($acount,38,190,'".AddSlashes(pg_result($resaco,$conresaco,'j46_dtini'))."','$this->j46_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,38,191,'".AddSlashes(pg_result($resaco,$conresaco,'j46_dtfim'))."','$this->j46_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_perc"]))
           $resac = db_query("insert into db_acount values($acount,38,192,'".AddSlashes(pg_result($resaco,$conresaco,'j46_perc'))."','$this->j46_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_dtinc"]))
           $resac = db_query("insert into db_acount values($acount,38,193,'".AddSlashes(pg_result($resaco,$conresaco,'j46_dtinc'))."','$this->j46_dtinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_idusu"]))
           $resac = db_query("insert into db_acount values($acount,38,194,'".AddSlashes(pg_result($resaco,$conresaco,'j46_idusu'))."','$this->j46_idusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_hist"]))
           $resac = db_query("insert into db_acount values($acount,38,195,'".AddSlashes(pg_result($resaco,$conresaco,'j46_hist'))."','$this->j46_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j46_arealo"]))
           $resac = db_query("insert into db_acount values($acount,38,5908,'".AddSlashes(pg_result($resaco,$conresaco,'j46_arealo'))."','$this->j46_arealo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j46_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($j46_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j46_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,187,'$j46_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,38,187,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,188,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,189,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,190,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,191,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,192,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,193,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,194,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_idusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,195,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,38,5908,'','".AddSlashes(pg_result($resaco,$iresaco,'j46_arealo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptuisen
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j46_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j46_codigo = $j46_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j46_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j46_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptuisen";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j46_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from iptuisen ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuisen.j46_matric";
     $sql .= "      inner join tipoisen  on  tipoisen.j45_tipo = iptuisen.j46_tipo";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      left join isentaxa on isentaxa.j56_codigo = iptuisen.j46_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($j46_codigo!=null ){
         $sql2 .= " where iptuisen.j46_codigo = $j46_codigo ";
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
   function sql_query_file ( $j46_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from iptuisen ";
     $sql2 = "";
     if($dbwhere==""){
       if($j46_codigo!=null ){
         $sql2 .= " where iptuisen.j46_codigo = $j46_codigo ";
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
   function sql_query_isen ( $j46_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from iptuisen ";
     $sql .= "      inner join tipoisen           on j45_tipo                = j46_tipo ";
     $sql .= "      inner join proprietario       on j01_matric              = j46_matric ";
     $sql .= "      left  join isenproc           on j61_codigo              = j46_codigo";
     $sql .= "   	  left  join promitente         on promitente.j41_matric   = j46_matric";
     $sql .= "     	left  join cgm                on promitente.j41_numcgm   = cgm.z01_numcgm";
     $sql .= "   	  left  join cgm as cgm_propri  on proprietario.z01_numcgm = cgm_propri.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j46_codigo!=null ){
         $sql2 .= " where iptuisen.j46_codigo = $j46_codigo ";
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

  public function sql_queryIsencao($iAnousu, $iMatricula) {

    $sSql  = "select j45_tipo, j45_descr                      ";
    $sSql .= "  from iptuisen                                 ";
    $sSql .= " inner join isenexe  on j46_codigo = j47_codigo ";
    $sSql .= " inner join tipoisen on j46_tipo   = j45_tipo   ";
    $sSql .= " where j46_matric = {$iMatricula}               ";
    $sSql .= "   and j47_anousu = {$iAnousu}                  ";

    return $sSql;

  }
}