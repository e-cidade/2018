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

//MODULO: educação
//CLASSE DA ENTIDADE efetividade
class cl_efetividade {
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
   var $ed97_i_codigo = 0;
   var $ed97_i_efetividaderh = 0;
   var $ed97_i_rechumano = 0;
   var $ed97_i_diasletivos = 0;
   var $ed97_i_faltaabon = 0;
   var $ed97_i_faltanjust = 0;
   var $ed97_t_licenca = null;
   var $ed97_t_horario = null;
   var $ed97_i_horacinq = 0;
   var $ed97_i_horacem = 0;
   var $ed97_t_obs = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed97_i_codigo = int8 = Código
                 ed97_i_efetividaderh = int8 = Efetividade
                 ed97_i_rechumano = int8 = Recurso Humano
                 ed97_i_diasletivos = int4 = Dias Letivos
                 ed97_i_faltaabon = int4 = Faltas Abonadas
                 ed97_i_faltanjust = int4 = Faltas Não Justificadas
                 ed97_t_licenca = text = Licença Saúde
                 ed97_t_horario = text = Horário
                 ed97_i_horacinq = float4 = Horas 50%
                 ed97_i_horacem = float4 = Horas 100%
                 ed97_t_obs = text = Observações
                 ";
   //funcao construtor da classe
   function cl_efetividade() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("efetividade");
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
       $this->ed97_i_codigo = ($this->ed97_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_codigo"]:$this->ed97_i_codigo);
       $this->ed97_i_efetividaderh = ($this->ed97_i_efetividaderh == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_efetividaderh"]:$this->ed97_i_efetividaderh);
       $this->ed97_i_rechumano = ($this->ed97_i_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_rechumano"]:$this->ed97_i_rechumano);
       $this->ed97_i_diasletivos = ($this->ed97_i_diasletivos == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_diasletivos"]:$this->ed97_i_diasletivos);
       $this->ed97_i_faltaabon = ($this->ed97_i_faltaabon == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_faltaabon"]:$this->ed97_i_faltaabon);
       $this->ed97_i_faltanjust = ($this->ed97_i_faltanjust == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_faltanjust"]:$this->ed97_i_faltanjust);
       $this->ed97_t_licenca = ($this->ed97_t_licenca == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_t_licenca"]:$this->ed97_t_licenca);
       $this->ed97_t_horario = ($this->ed97_t_horario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_t_horario"]:$this->ed97_t_horario);
       $this->ed97_i_horacinq = ($this->ed97_i_horacinq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_horacinq"]:$this->ed97_i_horacinq);
       $this->ed97_i_horacem = ($this->ed97_i_horacem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_horacem"]:$this->ed97_i_horacem);
       $this->ed97_t_obs = ($this->ed97_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_t_obs"]:$this->ed97_t_obs);
     }else{
       $this->ed97_i_codigo = ($this->ed97_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed97_i_codigo"]:$this->ed97_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed97_i_codigo){
      $this->atualizacampos();
     if($this->ed97_i_efetividaderh == null ){
       $this->erro_sql = " Campo Efetividade nao Informado.";
       $this->erro_campo = "ed97_i_efetividaderh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed97_i_rechumano == null ){
       $this->erro_sql = " Campo Recurso Humano nao Informado.";
       $this->erro_campo = "ed97_i_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed97_i_diasletivos == null ){
       $this->ed97_i_diasletivos = "0";
     }
     if($this->ed97_i_faltaabon == null ){
       $this->ed97_i_faltaabon = "0";
     }
     if($this->ed97_i_faltanjust == null ){
       $this->ed97_i_faltanjust = "0";
     }
     if($this->ed97_i_horacinq == null ){
       $this->ed97_i_horacinq = "0";
     }
     if($this->ed97_i_horacem == null ){
       $this->ed97_i_horacem = "0";
     }
     if($ed97_i_codigo == "" || $ed97_i_codigo == null ){
       $result = db_query("select nextval('efetividade_ed97_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: efetividade_ed97_i_codigo_seq do campo: ed97_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed97_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from efetividade_ed97_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed97_i_codigo)){
         $this->erro_sql = " Campo ed97_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed97_i_codigo = $ed97_i_codigo;
       }
     }
     if(($this->ed97_i_codigo == null) || ($this->ed97_i_codigo == "") ){
       $this->erro_sql = " Campo ed97_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into efetividade(
                                       ed97_i_codigo
                                      ,ed97_i_efetividaderh
                                      ,ed97_i_rechumano
                                      ,ed97_i_diasletivos
                                      ,ed97_i_faltaabon
                                      ,ed97_i_faltanjust
                                      ,ed97_t_licenca
                                      ,ed97_t_horario
                                      ,ed97_i_horacinq
                                      ,ed97_i_horacem
                                      ,ed97_t_obs
                       )
                values (
                                $this->ed97_i_codigo
                               ,$this->ed97_i_efetividaderh
                               ,$this->ed97_i_rechumano
                               ,$this->ed97_i_diasletivos
                               ,$this->ed97_i_faltaabon
                               ,$this->ed97_i_faltanjust
                               ,'$this->ed97_t_licenca'
                               ,'$this->ed97_t_horario'
                               ,$this->ed97_i_horacinq
                               ,$this->ed97_i_horacem
                               ,'$this->ed97_t_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Efetividade do RH ($this->ed97_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Efetividade do RH já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Efetividade do RH ($this->ed97_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed97_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed97_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008987,'$this->ed97_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010155,1008987,'','".AddSlashes(pg_result($resaco,0,'ed97_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1009006,'','".AddSlashes(pg_result($resaco,0,'ed97_i_efetividaderh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008988,'','".AddSlashes(pg_result($resaco,0,'ed97_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008991,'','".AddSlashes(pg_result($resaco,0,'ed97_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008990,'','".AddSlashes(pg_result($resaco,0,'ed97_i_faltaabon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008989,'','".AddSlashes(pg_result($resaco,0,'ed97_i_faltanjust'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008992,'','".AddSlashes(pg_result($resaco,0,'ed97_t_licenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008994,'','".AddSlashes(pg_result($resaco,0,'ed97_t_horario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008995,'','".AddSlashes(pg_result($resaco,0,'ed97_i_horacinq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008996,'','".AddSlashes(pg_result($resaco,0,'ed97_i_horacem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010155,1008993,'','".AddSlashes(pg_result($resaco,0,'ed97_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed97_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update efetividade set ";
     $virgula = "";
     if(trim($this->ed97_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_codigo"])){
       $sql  .= $virgula." ed97_i_codigo = $this->ed97_i_codigo ";
       $virgula = ",";
       if(trim($this->ed97_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed97_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed97_i_efetividaderh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_efetividaderh"])){
       $sql  .= $virgula." ed97_i_efetividaderh = $this->ed97_i_efetividaderh ";
       $virgula = ",";
       if(trim($this->ed97_i_efetividaderh) == null ){
         $this->erro_sql = " Campo Efetividade nao Informado.";
         $this->erro_campo = "ed97_i_efetividaderh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed97_i_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_rechumano"])){
       $sql  .= $virgula." ed97_i_rechumano = $this->ed97_i_rechumano ";
       $virgula = ",";
       if(trim($this->ed97_i_rechumano) == null ){
         $this->erro_sql = " Campo Recurso Humano nao Informado.";
         $this->erro_campo = "ed97_i_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed97_i_diasletivos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_diasletivos"])){
        if(trim($this->ed97_i_diasletivos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_diasletivos"])){
           $this->ed97_i_diasletivos = "0" ;
        }
       $sql  .= $virgula." ed97_i_diasletivos = $this->ed97_i_diasletivos ";
       $virgula = ",";
     }
     if(trim($this->ed97_i_faltaabon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_faltaabon"])){
        if(trim($this->ed97_i_faltaabon)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_faltaabon"])){
           $this->ed97_i_faltaabon = "0" ;
        }
       $sql  .= $virgula." ed97_i_faltaabon = $this->ed97_i_faltaabon ";
       $virgula = ",";
     }
     if(trim($this->ed97_i_faltanjust)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_faltanjust"])){
        if(trim($this->ed97_i_faltanjust)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_faltanjust"])){
           $this->ed97_i_faltanjust = "0" ;
        }
       $sql  .= $virgula." ed97_i_faltanjust = $this->ed97_i_faltanjust ";
       $virgula = ",";
     }
     if(trim($this->ed97_t_licenca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_t_licenca"])){
       $sql  .= $virgula." ed97_t_licenca = '$this->ed97_t_licenca' ";
       $virgula = ",";
     }
     if(trim($this->ed97_t_horario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_t_horario"])){
       $sql  .= $virgula." ed97_t_horario = '$this->ed97_t_horario' ";
       $virgula = ",";
     }
     if(trim($this->ed97_i_horacinq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_horacinq"])){
        if(trim($this->ed97_i_horacinq)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_horacinq"])){
           $this->ed97_i_horacinq = "0" ;
        }
       $sql  .= $virgula." ed97_i_horacinq = $this->ed97_i_horacinq ";
       $virgula = ",";
     }
     if(trim($this->ed97_i_horacem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_horacem"])){
        if(trim($this->ed97_i_horacem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_horacem"])){
           $this->ed97_i_horacem = "0" ;
        }
       $sql  .= $virgula." ed97_i_horacem = $this->ed97_i_horacem ";
       $virgula = ",";
     }
    
     $sql  .= $virgula." ed97_t_obs = '$this->ed97_t_obs' ";
     $virgula = ",";
     
     $sql .= " where ";
     if($ed97_i_codigo!=null){
       $sql .= " ed97_i_codigo = $this->ed97_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed97_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008987,'$this->ed97_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008987,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_i_codigo'))."','$this->ed97_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_efetividaderh"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1009006,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_i_efetividaderh'))."','$this->ed97_i_efetividaderh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_rechumano"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008988,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_i_rechumano'))."','$this->ed97_i_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_diasletivos"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008991,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_i_diasletivos'))."','$this->ed97_i_diasletivos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_faltaabon"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008990,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_i_faltaabon'))."','$this->ed97_i_faltaabon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_faltanjust"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008989,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_i_faltanjust'))."','$this->ed97_i_faltanjust',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_t_licenca"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008992,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_t_licenca'))."','$this->ed97_t_licenca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_t_horario"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008994,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_t_horario'))."','$this->ed97_t_horario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_horacinq"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008995,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_i_horacinq'))."','$this->ed97_i_horacinq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_i_horacem"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008996,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_i_horacem'))."','$this->ed97_i_horacem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed97_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1010155,1008993,'".AddSlashes(pg_result($resaco,$conresaco,'ed97_t_obs'))."','$this->ed97_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Efetividade do RH nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed97_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Efetividade do RH nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed97_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed97_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed97_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed97_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008987,'$ed97_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010155,1008987,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1009006,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_i_efetividaderh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008988,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008991,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008990,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_i_faltaabon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008989,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_i_faltanjust'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008992,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_t_licenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008994,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_t_horario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008995,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_i_horacinq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008996,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_i_horacem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010155,1008993,'','".AddSlashes(pg_result($resaco,$iresaco,'ed97_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from efetividade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed97_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed97_i_codigo = $ed97_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Efetividade do RH nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed97_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Efetividade do RH nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed97_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed97_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:efetividade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed97_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from efetividade ";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = efetividade.ed97_i_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      inner join efetividaderh  on  efetividaderh.ed98_i_codigo = efetividade.ed97_i_efetividaderh";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = efetividaderh.ed98_i_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($ed97_i_codigo!=null ){
         $sql2 .= " where efetividade.ed97_i_codigo = $ed97_i_codigo ";
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
   function sql_query_file ( $ed97_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from efetividade ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed97_i_codigo!=null ){
         $sql2 .= " where efetividade.ed97_i_codigo = $ed97_i_codigo "; 
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

  function sql_query_rechumanoescola( $ed97_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from efetividade ";
     $sql .= "      inner join rechumano       on rechumano.ed20_i_codigo = efetividade.ed97_i_rechumano ";
     $sql .= "      inner join rechumanoescola on rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed97_i_codigo!=null ){
         $sql2 .= " where efetividade.ed97_i_codigo = $ed97_i_codigo ";
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