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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE retiradas
class cl_retiradas {
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
   var $cm08_i_codigo = 0;
   var $cm08_i_sepultamento = 0;
   var $cm08_i_retirante = 0;
   var $cm08_c_parentesco = null;
   var $cm08_c_causa = null;
   var $cm08_c_destino = null;
   var $cm08_d_retirada_dia = null;
   var $cm08_d_retirada_mes = null;
   var $cm08_d_retirada_ano = null;
   var $cm08_d_retirada = null;
   var $cm08_t_obs = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm08_i_codigo = int4 = Código
                 cm08_i_sepultamento = int4 = Sepultamento
                 cm08_i_retirante = int4 = Retirante
                 cm08_c_parentesco = char(25) = Parentesco
                 cm08_c_causa = char(100) = Causa
                 cm08_c_destino = char(100) = Destino
                 cm08_d_retirada = date = Retirada
                 cm08_t_obs = text = Observações
                 ";
   //funcao construtor da classe
   function cl_retiradas() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("retiradas");
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
       $this->cm08_i_codigo = ($this->cm08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_i_codigo"]:$this->cm08_i_codigo);
       $this->cm08_i_sepultamento = ($this->cm08_i_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_i_sepultamento"]:$this->cm08_i_sepultamento);
       $this->cm08_i_retirante = ($this->cm08_i_retirante == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_i_retirante"]:$this->cm08_i_retirante);
       $this->cm08_c_parentesco = ($this->cm08_c_parentesco == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_c_parentesco"]:$this->cm08_c_parentesco);
       $this->cm08_c_causa = ($this->cm08_c_causa == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_c_causa"]:$this->cm08_c_causa);
       $this->cm08_c_destino = ($this->cm08_c_destino == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_c_destino"]:$this->cm08_c_destino);
       if($this->cm08_d_retirada == ""){
         $this->cm08_d_retirada_dia = ($this->cm08_d_retirada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_d_retirada_dia"]:$this->cm08_d_retirada_dia);
         $this->cm08_d_retirada_mes = ($this->cm08_d_retirada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_d_retirada_mes"]:$this->cm08_d_retirada_mes);
         $this->cm08_d_retirada_ano = ($this->cm08_d_retirada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_d_retirada_ano"]:$this->cm08_d_retirada_ano);
         if($this->cm08_d_retirada_dia != ""){
            $this->cm08_d_retirada = $this->cm08_d_retirada_ano."-".$this->cm08_d_retirada_mes."-".$this->cm08_d_retirada_dia;
         }
       }
       $this->cm08_t_obs = ($this->cm08_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_t_obs"]:$this->cm08_t_obs);
     }else{
       $this->cm08_i_codigo = ($this->cm08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm08_i_codigo"]:$this->cm08_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm08_i_codigo){
      $this->atualizacampos();
     if($this->cm08_i_sepultamento == null ){
       $this->erro_sql = " Campo Sepultamento nao Informado.";
       $this->erro_campo = "cm08_i_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm08_i_retirante == null ){
       $this->erro_sql = " Campo Retirante nao Informado.";
       $this->erro_campo = "cm08_i_retirante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm08_c_parentesco == null ){
       $this->erro_sql = " Campo Parentesco nao Informado.";
       $this->erro_campo = "cm08_c_parentesco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm08_c_causa == null ){
       $this->erro_sql = " Campo Causa nao Informado.";
       $this->erro_campo = "cm08_c_causa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm08_c_destino == null ){
       $this->erro_sql = " Campo Destino nao Informado.";
       $this->erro_campo = "cm08_c_destino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm08_d_retirada == null ){
       $this->erro_sql = " Campo Retirada nao Informado.";
       $this->erro_campo = "cm08_d_retirada_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm08_i_codigo == "" || $cm08_i_codigo == null ){
       $result = db_query("select nextval('retiradas_cm08_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: retiradas_cm08_i_codigo_seq do campo: cm08_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm08_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from retiradas_cm08_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm08_i_codigo)){
         $this->erro_sql = " Campo cm08_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm08_i_codigo = $cm08_i_codigo;
       }
     }
     if(($this->cm08_i_codigo == null) || ($this->cm08_i_codigo == "") ){
       $this->erro_sql = " Campo cm08_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into retiradas(
                                       cm08_i_codigo
                                      ,cm08_i_sepultamento
                                      ,cm08_i_retirante
                                      ,cm08_c_parentesco
                                      ,cm08_c_causa
                                      ,cm08_c_destino
                                      ,cm08_d_retirada
                                      ,cm08_t_obs
                       )
                values (
                                $this->cm08_i_codigo
                               ,$this->cm08_i_sepultamento
                               ,$this->cm08_i_retirante
                               ,'$this->cm08_c_parentesco'
                               ,'$this->cm08_c_causa'
                               ,'$this->cm08_c_destino'
                               ,".($this->cm08_d_retirada == "null" || $this->cm08_d_retirada == ""?"null":"'".$this->cm08_d_retirada."'")."
                               ,'$this->cm08_t_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Retiradas ($this->cm08_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Retiradas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Retiradas ($this->cm08_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm08_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm08_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10420,'$this->cm08_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1801,10420,'','".AddSlashes(pg_result($resaco,0,'cm08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1801,10421,'','".AddSlashes(pg_result($resaco,0,'cm08_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1801,10422,'','".AddSlashes(pg_result($resaco,0,'cm08_i_retirante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1801,10423,'','".AddSlashes(pg_result($resaco,0,'cm08_c_parentesco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1801,10424,'','".AddSlashes(pg_result($resaco,0,'cm08_c_causa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1801,10425,'','".AddSlashes(pg_result($resaco,0,'cm08_c_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1801,10426,'','".AddSlashes(pg_result($resaco,0,'cm08_d_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1801,10427,'','".AddSlashes(pg_result($resaco,0,'cm08_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm08_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update retiradas set ";
     $virgula = "";
     if(trim($this->cm08_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm08_i_codigo"])){
       $sql  .= $virgula." cm08_i_codigo = $this->cm08_i_codigo ";
       $virgula = ",";
       if(trim($this->cm08_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm08_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm08_i_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm08_i_sepultamento"])){
       $sql  .= $virgula." cm08_i_sepultamento = $this->cm08_i_sepultamento ";
       $virgula = ",";
       if(trim($this->cm08_i_sepultamento) == null ){
         $this->erro_sql = " Campo Sepultamento nao Informado.";
         $this->erro_campo = "cm08_i_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm08_i_retirante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm08_i_retirante"])){
       $sql  .= $virgula." cm08_i_retirante = $this->cm08_i_retirante ";
       $virgula = ",";
       if(trim($this->cm08_i_retirante) == null ){
         $this->erro_sql = " Campo Retirante nao Informado.";
         $this->erro_campo = "cm08_i_retirante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm08_c_parentesco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm08_c_parentesco"])){
       $sql  .= $virgula." cm08_c_parentesco = '$this->cm08_c_parentesco' ";
       $virgula = ",";
       if(trim($this->cm08_c_parentesco) == null ){
         $this->erro_sql = " Campo Parentesco nao Informado.";
         $this->erro_campo = "cm08_c_parentesco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm08_c_causa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm08_c_causa"])){
       $sql  .= $virgula." cm08_c_causa = '$this->cm08_c_causa' ";
       $virgula = ",";
       if(trim($this->cm08_c_causa) == null ){
         $this->erro_sql = " Campo Causa nao Informado.";
         $this->erro_campo = "cm08_c_causa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm08_c_destino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm08_c_destino"])){
       $sql  .= $virgula." cm08_c_destino = '$this->cm08_c_destino' ";
       $virgula = ",";
       if(trim($this->cm08_c_destino) == null ){
         $this->erro_sql = " Campo Destino nao Informado.";
         $this->erro_campo = "cm08_c_destino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm08_d_retirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm08_d_retirada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm08_d_retirada_dia"] !="") ){
       $sql  .= $virgula." cm08_d_retirada = '$this->cm08_d_retirada' ";
       $virgula = ",";
       if(trim($this->cm08_d_retirada) == null ){
         $this->erro_sql = " Campo Retirada nao Informado.";
         $this->erro_campo = "cm08_d_retirada_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_d_retirada_dia"])){
         $sql  .= $virgula." cm08_d_retirada = null ";
         $virgula = ",";
         if(trim($this->cm08_d_retirada) == null ){
           $this->erro_sql = " Campo Retirada nao Informado.";
           $this->erro_campo = "cm08_d_retirada_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm08_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm08_t_obs"])){
       $sql  .= $virgula." cm08_t_obs = '$this->cm08_t_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($cm08_i_codigo!=null){
       $sql .= " cm08_i_codigo = $this->cm08_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm08_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10420,'$this->cm08_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1801,10420,'".AddSlashes(pg_result($resaco,$conresaco,'cm08_i_codigo'))."','$this->cm08_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_i_sepultamento"]))
           $resac = db_query("insert into db_acount values($acount,1801,10421,'".AddSlashes(pg_result($resaco,$conresaco,'cm08_i_sepultamento'))."','$this->cm08_i_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_i_retirante"]))
           $resac = db_query("insert into db_acount values($acount,1801,10422,'".AddSlashes(pg_result($resaco,$conresaco,'cm08_i_retirante'))."','$this->cm08_i_retirante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_c_parentesco"]))
           $resac = db_query("insert into db_acount values($acount,1801,10423,'".AddSlashes(pg_result($resaco,$conresaco,'cm08_c_parentesco'))."','$this->cm08_c_parentesco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_c_causa"]))
           $resac = db_query("insert into db_acount values($acount,1801,10424,'".AddSlashes(pg_result($resaco,$conresaco,'cm08_c_causa'))."','$this->cm08_c_causa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_c_destino"]))
           $resac = db_query("insert into db_acount values($acount,1801,10425,'".AddSlashes(pg_result($resaco,$conresaco,'cm08_c_destino'))."','$this->cm08_c_destino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_d_retirada"]))
           $resac = db_query("insert into db_acount values($acount,1801,10426,'".AddSlashes(pg_result($resaco,$conresaco,'cm08_d_retirada'))."','$this->cm08_d_retirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm08_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1801,10427,'".AddSlashes(pg_result($resaco,$conresaco,'cm08_t_obs'))."','$this->cm08_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retiradas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Retiradas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm08_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm08_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10420,'$cm08_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1801,10420,'','".AddSlashes(pg_result($resaco,$iresaco,'cm08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1801,10421,'','".AddSlashes(pg_result($resaco,$iresaco,'cm08_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1801,10422,'','".AddSlashes(pg_result($resaco,$iresaco,'cm08_i_retirante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1801,10423,'','".AddSlashes(pg_result($resaco,$iresaco,'cm08_c_parentesco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1801,10424,'','".AddSlashes(pg_result($resaco,$iresaco,'cm08_c_causa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1801,10425,'','".AddSlashes(pg_result($resaco,$iresaco,'cm08_c_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1801,10426,'','".AddSlashes(pg_result($resaco,$iresaco,'cm08_d_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1801,10427,'','".AddSlashes(pg_result($resaco,$iresaco,'cm08_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from retiradas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm08_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm08_i_codigo = $cm08_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retiradas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Retiradas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm08_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:retiradas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm08_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from retiradas ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = retiradas.cm08_i_retirante";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = retiradas.cm08_i_sepultamento";
     $sql .= "      inner join cgm cgmsepultado  on  cgmsepultado.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      left  join legista  on  legista.cm32_i_codigo = sepultamentos.cm01_i_medico";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      left  join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql .= "      left  join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql2 = "";
     if($dbwhere==""){
       if($cm08_i_codigo!=null ){
         $sql2 .= " where retiradas.cm08_i_codigo = $cm08_i_codigo ";
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
   function sql_query_file ( $cm08_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from retiradas ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm08_i_codigo!=null ){
         $sql2 .= " where retiradas.cm08_i_codigo = $cm08_i_codigo ";
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
