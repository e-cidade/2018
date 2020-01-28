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
 *  junto com este programa; se não, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_receitamedica
class cl_sau_receitamedica {
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
   var $s158_i_codigo = 0;
   var $s158_i_profissional = 0;
   var $s158_i_tiporeceita = 0;
   var $s158_t_prescricao = null;
   var $s158_i_situacao = 0;
   var $s158_d_validade_dia = null;
   var $s158_d_validade_mes = null;
   var $s158_d_validade_ano = null;
   var $s158_d_validade = null;
   var $s158_i_login = 0;
   var $s158_d_data_dia = null;
   var $s158_d_data_mes = null;
   var $s158_d_data_ano = null;
   var $s158_d_data = null;
   var $s158_c_hora = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 s158_i_codigo = int4 = Código
                 s158_i_profissional = int4 = Profissional
                 s158_i_tiporeceita = int4 = Tipo de Receita
                 s158_t_prescricao = text = Prescrição
                 s158_i_situacao = int4 = Situação
                 s158_d_validade = date = Validade
                 s158_i_login = int4 = Login
                 s158_d_data = date = Data do sistema
                 s158_c_hora = char(5) = Hora do sistema
                 ";
   //funcao construtor da classe
   function cl_sau_receitamedica() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_receitamedica");
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
       $this->s158_i_codigo = ($this->s158_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_i_codigo"]:$this->s158_i_codigo);
       $this->s158_i_profissional = ($this->s158_i_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_i_profissional"]:$this->s158_i_profissional);
       $this->s158_i_tiporeceita = ($this->s158_i_tiporeceita == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_i_tiporeceita"]:$this->s158_i_tiporeceita);
       $this->s158_t_prescricao = ($this->s158_t_prescricao == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_t_prescricao"]:$this->s158_t_prescricao);
       $this->s158_i_situacao = ($this->s158_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_i_situacao"]:$this->s158_i_situacao);
       if($this->s158_d_validade == ""){
         $this->s158_d_validade_dia = ($this->s158_d_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_d_validade_dia"]:$this->s158_d_validade_dia);
         $this->s158_d_validade_mes = ($this->s158_d_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_d_validade_mes"]:$this->s158_d_validade_mes);
         $this->s158_d_validade_ano = ($this->s158_d_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_d_validade_ano"]:$this->s158_d_validade_ano);
         if($this->s158_d_validade_dia != ""){
            $this->s158_d_validade = $this->s158_d_validade_ano."-".$this->s158_d_validade_mes."-".$this->s158_d_validade_dia;
         }
       }
       $this->s158_i_login = ($this->s158_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_i_login"]:$this->s158_i_login);
       if($this->s158_d_data == ""){
         $this->s158_d_data_dia = ($this->s158_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_d_data_dia"]:$this->s158_d_data_dia);
         $this->s158_d_data_mes = ($this->s158_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_d_data_mes"]:$this->s158_d_data_mes);
         $this->s158_d_data_ano = ($this->s158_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_d_data_ano"]:$this->s158_d_data_ano);
         if($this->s158_d_data_dia != ""){
            $this->s158_d_data = $this->s158_d_data_ano."-".$this->s158_d_data_mes."-".$this->s158_d_data_dia;
         }
       }
       $this->s158_c_hora = ($this->s158_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_c_hora"]:$this->s158_c_hora);
     }else{
       $this->s158_i_codigo = ($this->s158_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s158_i_codigo"]:$this->s158_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s158_i_codigo){
      $this->atualizacampos();
     if($this->s158_i_profissional == null ){
       $this->erro_sql = " Campo Profissional não Informado.";
       $this->erro_campo = "s158_i_profissional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s158_i_tiporeceita == null ){
       $this->erro_sql = " Campo Tipo de Receita não Informado.";
       $this->erro_campo = "s158_i_tiporeceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s158_t_prescricao == null ){
       $this->erro_sql = " Campo Prescrição não Informado.";
       $this->erro_campo = "s158_t_prescricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s158_i_situacao == null ){
       $this->erro_sql = " Campo Situação não Informado.";
       $this->erro_campo = "s158_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s158_d_validade == null ){
       $this->erro_sql = " Campo Validade não Informado.";
       $this->erro_campo = "s158_d_validade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s158_i_login == null ){
       $this->erro_sql = " Campo Login não Informado.";
       $this->erro_campo = "s158_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s158_d_data == null ){
       $this->erro_sql = " Campo Data do sistema não Informado.";
       $this->erro_campo = "s158_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s158_c_hora == null ){
       $this->erro_sql = " Campo Hora do sistema não Informado.";
       $this->erro_campo = "s158_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s158_i_codigo == "" || $s158_i_codigo == null ){
       $result = db_query("select nextval('sau_receitamedica_s158_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_receitamedica_s158_i_codigo_seq do campo: s158_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->s158_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sau_receitamedica_s158_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s158_i_codigo)){
         $this->erro_sql = " Campo s158_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s158_i_codigo = $s158_i_codigo;
       }
     }
     if(($this->s158_i_codigo == null) || ($this->s158_i_codigo == "") ){
       $this->erro_sql = " Campo s158_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_receitamedica(
                                       s158_i_codigo
                                      ,s158_i_profissional
                                      ,s158_i_tiporeceita
                                      ,s158_t_prescricao
                                      ,s158_i_situacao
                                      ,s158_d_validade
                                      ,s158_i_login
                                      ,s158_d_data
                                      ,s158_c_hora
                       )
                values (
                                $this->s158_i_codigo
                               ,$this->s158_i_profissional
                               ,$this->s158_i_tiporeceita
                               ,'$this->s158_t_prescricao'
                               ,$this->s158_i_situacao
                               ,".($this->s158_d_validade == "null" || $this->s158_d_validade == ""?"null":"'".$this->s158_d_validade."'")."
                               ,$this->s158_i_login
                               ,".($this->s158_d_data == "null" || $this->s158_d_data == ""?"null":"'".$this->s158_d_data."'")."
                               ,'$this->s158_c_hora'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_receitamedica ($this->s158_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_receitamedica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_receitamedica ($this->s158_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s158_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s158_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17728,'$this->s158_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3130,17728,'','".AddSlashes(pg_result($resaco,0,'s158_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3130,17730,'','".AddSlashes(pg_result($resaco,0,'s158_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3130,17731,'','".AddSlashes(pg_result($resaco,0,'s158_i_tiporeceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3130,17734,'','".AddSlashes(pg_result($resaco,0,'s158_t_prescricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3130,17733,'','".AddSlashes(pg_result($resaco,0,'s158_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3130,17732,'','".AddSlashes(pg_result($resaco,0,'s158_d_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3130,17729,'','".AddSlashes(pg_result($resaco,0,'s158_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3130,17735,'','".AddSlashes(pg_result($resaco,0,'s158_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3130,17736,'','".AddSlashes(pg_result($resaco,0,'s158_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($s158_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sau_receitamedica set ";
     $virgula = "";
     if(trim($this->s158_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_i_codigo"])){
       $sql  .= $virgula." s158_i_codigo = $this->s158_i_codigo ";
       $virgula = ",";
       if(trim($this->s158_i_codigo) == null ){
         $this->erro_sql = " Campo Código não Informado.";
         $this->erro_campo = "s158_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s158_i_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_i_profissional"])){
       $sql  .= $virgula." s158_i_profissional = $this->s158_i_profissional ";
       $virgula = ",";
       if(trim($this->s158_i_profissional) == null ){
         $this->erro_sql = " Campo Profissional não Informado.";
         $this->erro_campo = "s158_i_profissional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s158_i_tiporeceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_i_tiporeceita"])){
       $sql  .= $virgula." s158_i_tiporeceita = $this->s158_i_tiporeceita ";
       $virgula = ",";
       if(trim($this->s158_i_tiporeceita) == null ){
         $this->erro_sql = " Campo Tipo de Receita não Informado.";
         $this->erro_campo = "s158_i_tiporeceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s158_t_prescricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_t_prescricao"])){
       $sql  .= $virgula." s158_t_prescricao = '$this->s158_t_prescricao' ";
       $virgula = ",";
       if(trim($this->s158_t_prescricao) == null ){
         $this->erro_sql = " Campo Prescrição não Informado.";
         $this->erro_campo = "s158_t_prescricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s158_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_i_situacao"])){
       $sql  .= $virgula." s158_i_situacao = $this->s158_i_situacao ";
       $virgula = ",";
       if(trim($this->s158_i_situacao) == null ){
         $this->erro_sql = " Campo Situação não Informado.";
         $this->erro_campo = "s158_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s158_d_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_d_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s158_d_validade_dia"] !="") ){
       $sql  .= $virgula." s158_d_validade = '$this->s158_d_validade' ";
       $virgula = ",";
       if(trim($this->s158_d_validade) == null ){
         $this->erro_sql = " Campo Validade não Informado.";
         $this->erro_campo = "s158_d_validade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["s158_d_validade_dia"])){
         $sql  .= $virgula." s158_d_validade = null ";
         $virgula = ",";
         if(trim($this->s158_d_validade) == null ){
           $this->erro_sql = " Campo Validade não Informado.";
           $this->erro_campo = "s158_d_validade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s158_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_i_login"])){
       $sql  .= $virgula." s158_i_login = $this->s158_i_login ";
       $virgula = ",";
       if(trim($this->s158_i_login) == null ){
         $this->erro_sql = " Campo Login não Informado.";
         $this->erro_campo = "s158_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s158_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s158_d_data_dia"] !="") ){
       $sql  .= $virgula." s158_d_data = '$this->s158_d_data' ";
       $virgula = ",";
       if(trim($this->s158_d_data) == null ){
         $this->erro_sql = " Campo Data do sistema não Informado.";
         $this->erro_campo = "s158_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["s158_d_data_dia"])){
         $sql  .= $virgula." s158_d_data = null ";
         $virgula = ",";
         if(trim($this->s158_d_data) == null ){
           $this->erro_sql = " Campo Data do sistema não Informado.";
           $this->erro_campo = "s158_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s158_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s158_c_hora"])){
       $sql  .= $virgula." s158_c_hora = '$this->s158_c_hora' ";
       $virgula = ",";
       if(trim($this->s158_c_hora) == null ){
         $this->erro_sql = " Campo Hora do sistema não Informado.";
         $this->erro_campo = "s158_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s158_i_codigo!=null){
       $sql .= " s158_i_codigo = $this->s158_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s158_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17728,'$this->s158_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_i_codigo"]) || $this->s158_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3130,17728,'".AddSlashes(pg_result($resaco,$conresaco,'s158_i_codigo'))."','$this->s158_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_i_profissional"]) || $this->s158_i_profissional != "")
           $resac = db_query("insert into db_acount values($acount,3130,17730,'".AddSlashes(pg_result($resaco,$conresaco,'s158_i_profissional'))."','$this->s158_i_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_i_tiporeceita"]) || $this->s158_i_tiporeceita != "")
           $resac = db_query("insert into db_acount values($acount,3130,17731,'".AddSlashes(pg_result($resaco,$conresaco,'s158_i_tiporeceita'))."','$this->s158_i_tiporeceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_t_prescricao"]) || $this->s158_t_prescricao != "")
           $resac = db_query("insert into db_acount values($acount,3130,17734,'".AddSlashes(pg_result($resaco,$conresaco,'s158_t_prescricao'))."','$this->s158_t_prescricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_i_situacao"]) || $this->s158_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,3130,17733,'".AddSlashes(pg_result($resaco,$conresaco,'s158_i_situacao'))."','$this->s158_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_d_validade"]) || $this->s158_d_validade != "")
           $resac = db_query("insert into db_acount values($acount,3130,17732,'".AddSlashes(pg_result($resaco,$conresaco,'s158_d_validade'))."','$this->s158_d_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_i_login"]) || $this->s158_i_login != "")
           $resac = db_query("insert into db_acount values($acount,3130,17729,'".AddSlashes(pg_result($resaco,$conresaco,'s158_i_login'))."','$this->s158_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_d_data"]) || $this->s158_d_data != "")
           $resac = db_query("insert into db_acount values($acount,3130,17735,'".AddSlashes(pg_result($resaco,$conresaco,'s158_d_data'))."','$this->s158_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s158_c_hora"]) || $this->s158_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,3130,17736,'".AddSlashes(pg_result($resaco,$conresaco,'s158_c_hora'))."','$this->s158_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_receitamedica não Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s158_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_receitamedica não foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s158_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s158_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($s158_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s158_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17728,'$s158_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3130,17728,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3130,17730,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3130,17731,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_i_tiporeceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3130,17734,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_t_prescricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3130,17733,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3130,17732,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_d_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3130,17729,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3130,17735,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3130,17736,'','".AddSlashes(pg_result($resaco,$iresaco,'s158_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_receitamedica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s158_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s158_i_codigo = $s158_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_receitamedica não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s158_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_receitamedica não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s158_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s158_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_receitamedica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $s158_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_receitamedica ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_receitamedica.s158_i_login";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = sau_receitamedica.s158_i_tiporeceita";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = sau_receitamedica.s158_i_profissional";
     $sql .= "      left  join far_prescricaomedica  on  far_prescricaomedica.fa20_i_codigo = far_tiporeceita.fa03_i_prescricaomedica";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($s158_i_codigo!=null ){
         $sql2 .= " where sau_receitamedica.s158_i_codigo = $s158_i_codigo ";
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
   function sql_query_file ( $s158_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_receitamedica ";
     $sql2 = "";
     if($dbwhere==""){
       if($s158_i_codigo!=null ){
         $sql2 .= " where sau_receitamedica.s158_i_codigo = $s158_i_codigo ";
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

  function sql_query_prontuario ( $s158_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_receitamedica ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_receitamedica.s158_i_login";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = sau_receitamedica.s158_i_tiporeceita";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = sau_receitamedica.s158_i_profissional";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      inner join sau_receitaprontuario  on  sau_receitaprontuario.s162_i_receita = sau_receitamedica.s158_i_codigo";
     $sql .= "      inner join prontuarios on  prontuarios.sd24_i_codigo = sau_receitaprontuario.s162_i_prontuario";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = prontuarios.sd24_i_numcgs ";
     $sql2 = "";
     if($dbwhere==""){
       if($s158_i_codigo!=null ){
         $sql2 .= " where sau_receitamedica.s158_i_codigo = $s158_i_codigo ";
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

  function sql_query_medicamentos ( $s158_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

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
    $sql .= " from sau_receitamedica ";
    $sql .= "   inner join db_usuarios  on  db_usuarios.id_usuario = sau_receitamedica.s158_i_login";
    $sql .= "   inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = sau_receitamedica.s158_i_tiporeceita";
    $sql .= "   inner join medicos  on  medicos.sd03_i_codigo = sau_receitamedica.s158_i_profissional";
    $sql .= "   inner join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
    $sql .= "   inner join sau_receitaprontuario  on  sau_receitaprontuario.s162_i_receita = sau_receitamedica.s158_i_codigo";
    $sql .= "   inner join prontuarios on  prontuarios.sd24_i_codigo = sau_receitaprontuario.s162_i_prontuario";
    $sql .= "   inner join cgs_und  on  cgs_und.z01_i_cgsund = prontuarios.sd24_i_numcgs ";
    $sql .= "   left  join sau_medicamentosreceita on sau_medicamentosreceita.s159_i_receita = sau_receitamedica.s158_i_codigo ";
    $sql .= "   left  join far_matersaude on far_matersaude.fa01_i_codigo = sau_medicamentosreceita.s159_i_medicamento ";
    $sql .= "   left  join matmater on matmater.m60_codmater = far_matersaude.fa01_i_codmater ";
    $sql .= "   left  join sau_formaadmmedicamento on sau_formaadmmedicamento.s160_i_codigo = sau_medicamentosreceita.s159_i_formaadm ";

    $sql2 = "";
    if($dbwhere==""){
      if($s158_i_codigo!=null ){
        $sql2 .= " where sau_receitamedica.s158_i_codigo = $s158_i_codigo ";
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