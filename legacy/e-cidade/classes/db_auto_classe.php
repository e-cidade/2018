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

//MODULO: fiscal
//CLASSE DA ENTIDADE auto
class cl_auto {
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
   var $y50_codauto = 0;
   var $y50_data_dia = null;
   var $y50_data_mes = null;
   var $y50_data_ano = null;
   var $y50_data = null;
   var $y50_hora = null;
   var $y50_obs = null;
   var $y50_setor = 0;
   var $y50_nome = null;
   var $y50_dtvenc_dia = null;
   var $y50_dtvenc_mes = null;
   var $y50_dtvenc_ano = null;
   var $y50_dtvenc = null;
   var $y50_numbloco = null;
   var $y50_prazorec_dia = null;
   var $y50_prazorec_mes = null;
   var $y50_prazorec_ano = null;
   var $y50_prazorec = null;
   var $y50_codtipo = 0;
   var $y50_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 y50_codauto = int4 = Código do Auto de Infração
                 y50_data = date = Data do Auto de Infração
                 y50_hora = char(5) = Hora do Auto
                 y50_obs = text = Observação do auto
                 y50_setor = int4 = Código do Departamento
                 y50_nome = varchar(50) = Nome da Pessoa Autuada
                 y50_dtvenc = date = Data do Vencimento Atualizada
                 y50_numbloco = varchar(20) = Número do Bloco
                 y50_prazorec = date = Prazo p/ Recurso
                 y50_codtipo = int4 = Cod. Tipo
                 y50_instit = int4 = Cod. Instituição
                 ";
   //funcao construtor da classe
   function cl_auto() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("auto");
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
       $this->y50_codauto = ($this->y50_codauto == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_codauto"]:$this->y50_codauto);
       if($this->y50_data == ""){
         $this->y50_data_dia = ($this->y50_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_data_dia"]:$this->y50_data_dia);
         $this->y50_data_mes = ($this->y50_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_data_mes"]:$this->y50_data_mes);
         $this->y50_data_ano = ($this->y50_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_data_ano"]:$this->y50_data_ano);
         if($this->y50_data_dia != ""){
            $this->y50_data = $this->y50_data_ano."-".$this->y50_data_mes."-".$this->y50_data_dia;
         }
       }
       $this->y50_hora = ($this->y50_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_hora"]:$this->y50_hora);
       $this->y50_obs = ($this->y50_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_obs"]:$this->y50_obs);
       $this->y50_setor = ($this->y50_setor == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_setor"]:$this->y50_setor);
       $this->y50_nome = ($this->y50_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_nome"]:$this->y50_nome);
       if($this->y50_dtvenc == ""){
         $this->y50_dtvenc_dia = ($this->y50_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_dtvenc_dia"]:$this->y50_dtvenc_dia);
         $this->y50_dtvenc_mes = ($this->y50_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_dtvenc_mes"]:$this->y50_dtvenc_mes);
         $this->y50_dtvenc_ano = ($this->y50_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_dtvenc_ano"]:$this->y50_dtvenc_ano);
         if($this->y50_dtvenc_dia != ""){
            $this->y50_dtvenc = $this->y50_dtvenc_ano."-".$this->y50_dtvenc_mes."-".$this->y50_dtvenc_dia;
         }
       }
       $this->y50_numbloco = ($this->y50_numbloco == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_numbloco"]:$this->y50_numbloco);
       if($this->y50_prazorec == ""){
         $this->y50_prazorec_dia = ($this->y50_prazorec_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_prazorec_dia"]:$this->y50_prazorec_dia);
         $this->y50_prazorec_mes = ($this->y50_prazorec_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_prazorec_mes"]:$this->y50_prazorec_mes);
         $this->y50_prazorec_ano = ($this->y50_prazorec_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_prazorec_ano"]:$this->y50_prazorec_ano);
         if($this->y50_prazorec_dia != ""){
            $this->y50_prazorec = $this->y50_prazorec_ano."-".$this->y50_prazorec_mes."-".$this->y50_prazorec_dia;
         }
       }
       $this->y50_codtipo = ($this->y50_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_codtipo"]:$this->y50_codtipo);
       $this->y50_instit = ($this->y50_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_instit"]:$this->y50_instit);
     }else{
       $this->y50_codauto = ($this->y50_codauto == ""?@$GLOBALS["HTTP_POST_VARS"]["y50_codauto"]:$this->y50_codauto);
     }
   }
   // funcao para inclusao
   function incluir ($y50_codauto){
      $this->atualizacampos();
     if($this->y50_data == null ){
       $this->erro_sql = " Campo Data do Auto de Infração nao Informado.";
       $this->erro_campo = "y50_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y50_hora == null ){
       $this->erro_sql = " Campo Hora do Auto nao Informado.";
       $this->erro_campo = "y50_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y50_setor == null ){
       $this->erro_sql = " Campo Código do Departamento nao Informado.";
       $this->erro_campo = "y50_setor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y50_nome == null ){
       $this->erro_sql = " Campo Nome da Pessoa Autuada nao Informado.";
       $this->erro_campo = "y50_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y50_dtvenc == null ){
       $this->erro_sql = " Campo Data do Vencimento Atualizada nao Informado.";
       $this->erro_campo = "y50_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y50_numbloco == null ){
       $this->erro_sql = " Campo Número do Bloco nao Informado.";
       $this->erro_campo = "y50_numbloco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y50_prazorec == null ){
       $this->y50_prazorec = "null";
     }
     if($this->y50_codtipo == null ){
       $this->erro_sql = " Campo Cod. Tipo nao Informado.";
       $this->erro_campo = "y50_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y50_instit == null ){
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "y50_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y50_codauto == "" || $y50_codauto == null ){
       $result = db_query("select nextval('auto_y50_codauto_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: auto_y50_codauto_seq do campo: y50_codauto";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->y50_codauto = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from auto_y50_codauto_seq");
       if(($result != false) && (pg_result($result,0,0) < $y50_codauto)){
         $this->erro_sql = " Campo y50_codauto maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y50_codauto = $y50_codauto;
       }
     }
     if(($this->y50_codauto == null) || ($this->y50_codauto == "") ){
       $this->erro_sql = " Campo y50_codauto nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into auto(
                                       y50_codauto
                                      ,y50_data
                                      ,y50_hora
                                      ,y50_obs
                                      ,y50_setor
                                      ,y50_nome
                                      ,y50_dtvenc
                                      ,y50_numbloco
                                      ,y50_prazorec
                                      ,y50_codtipo
                                      ,y50_instit
                       )
                values (
                                $this->y50_codauto
                               ,".($this->y50_data == "null" || $this->y50_data == ""?"null":"'".$this->y50_data."'")."
                               ,'$this->y50_hora'
                               ,'$this->y50_obs'
                               ,$this->y50_setor
                               ,'$this->y50_nome'
                               ,".($this->y50_dtvenc == "null" || $this->y50_dtvenc == ""?"null":"'".$this->y50_dtvenc."'")."
                               ,'$this->y50_numbloco'
                               ,".($this->y50_prazorec == "null" || $this->y50_prazorec == ""?"null":"'".$this->y50_prazorec."'")."
                               ,$this->y50_codtipo
                               ,$this->y50_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "auto ($this->y50_codauto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "auto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "auto ($this->y50_codauto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y50_codauto;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y50_codauto));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4985,'$this->y50_codauto','I')");
       $resac = db_query("insert into db_acount values($acount,699,4985,'','".AddSlashes(pg_result($resaco,0,'y50_codauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,4986,'','".AddSlashes(pg_result($resaco,0,'y50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,4987,'','".AddSlashes(pg_result($resaco,0,'y50_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,4988,'','".AddSlashes(pg_result($resaco,0,'y50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,4989,'','".AddSlashes(pg_result($resaco,0,'y50_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,4990,'','".AddSlashes(pg_result($resaco,0,'y50_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,4992,'','".AddSlashes(pg_result($resaco,0,'y50_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,5137,'','".AddSlashes(pg_result($resaco,0,'y50_numbloco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,6788,'','".AddSlashes(pg_result($resaco,0,'y50_prazorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,6792,'','".AddSlashes(pg_result($resaco,0,'y50_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,699,10667,'','".AddSlashes(pg_result($resaco,0,'y50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($y50_codauto=null) {
      $this->atualizacampos();
     $sql = " update auto set ";
     $virgula = "";
     if(trim($this->y50_codauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_codauto"])){
       $sql  .= $virgula." y50_codauto = $this->y50_codauto ";
       $virgula = ",";
       if(trim($this->y50_codauto) == null ){
         $this->erro_sql = " Campo Código do Auto de Infração nao Informado.";
         $this->erro_campo = "y50_codauto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y50_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y50_data_dia"] !="") ){
       $sql  .= $virgula." y50_data = '$this->y50_data' ";
       $virgula = ",";
       if(trim($this->y50_data) == null ){
         $this->erro_sql = " Campo Data do Auto de Infração nao Informado.";
         $this->erro_campo = "y50_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["y50_data_dia"])){
         $sql  .= $virgula." y50_data = null ";
         $virgula = ",";
         if(trim($this->y50_data) == null ){
           $this->erro_sql = " Campo Data do Auto de Infração nao Informado.";
           $this->erro_campo = "y50_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y50_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_hora"])){
       $sql  .= $virgula." y50_hora = '$this->y50_hora' ";
       $virgula = ",";
       if(trim($this->y50_hora) == null ){
         $this->erro_sql = " Campo Hora do Auto nao Informado.";
         $this->erro_campo = "y50_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y50_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_obs"])){
       $sql  .= $virgula." y50_obs = '$this->y50_obs' ";
       $virgula = ",";
     }
     if(trim($this->y50_setor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_setor"])){
       $sql  .= $virgula." y50_setor = $this->y50_setor ";
       $virgula = ",";
       if(trim($this->y50_setor) == null ){
         $this->erro_sql = " Campo Código do Departamento nao Informado.";
         $this->erro_campo = "y50_setor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y50_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_nome"])){
       $sql  .= $virgula." y50_nome = '$this->y50_nome' ";
       $virgula = ",";
       if(trim($this->y50_nome) == null ){
         $this->erro_sql = " Campo Nome da Pessoa Autuada nao Informado.";
         $this->erro_campo = "y50_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y50_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y50_dtvenc_dia"] !="") ){
       $sql  .= $virgula." y50_dtvenc = '$this->y50_dtvenc' ";
       $virgula = ",";
       if(trim($this->y50_dtvenc) == null ){
         $this->erro_sql = " Campo Data do Vencimento Atualizada nao Informado.";
         $this->erro_campo = "y50_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["y50_dtvenc_dia"])){
         $sql  .= $virgula." y50_dtvenc = null ";
         $virgula = ",";
         if(trim($this->y50_dtvenc) == null ){
           $this->erro_sql = " Campo Data do Vencimento Atualizada nao Informado.";
           $this->erro_campo = "y50_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y50_numbloco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_numbloco"])){
       $sql  .= $virgula." y50_numbloco = '$this->y50_numbloco' ";
       $virgula = ",";
       if(trim($this->y50_numbloco) == null ){
         $this->erro_sql = " Campo Número do Bloco nao Informado.";
         $this->erro_campo = "y50_numbloco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y50_prazorec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_prazorec_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y50_prazorec_dia"] !="") ){
       $sql  .= $virgula." y50_prazorec = '$this->y50_prazorec' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["y50_prazorec_dia"])){
         $sql  .= $virgula." y50_prazorec = null ";
         $virgula = ",";
       }
     }
     if(trim($this->y50_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_codtipo"])){
       $sql  .= $virgula." y50_codtipo = $this->y50_codtipo ";
       $virgula = ",";
       if(trim($this->y50_codtipo) == null ){
         $this->erro_sql = " Campo Cod. Tipo nao Informado.";
         $this->erro_campo = "y50_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y50_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y50_instit"])){
       $sql  .= $virgula." y50_instit = $this->y50_instit ";
       $virgula = ",";
       if(trim($this->y50_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "y50_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y50_codauto!=null){
       $sql .= " y50_codauto = $this->y50_codauto";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y50_codauto));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4985,'$this->y50_codauto','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_codauto"]))
           $resac = db_query("insert into db_acount values($acount,699,4985,'".AddSlashes(pg_result($resaco,$conresaco,'y50_codauto'))."','$this->y50_codauto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_data"]))
           $resac = db_query("insert into db_acount values($acount,699,4986,'".AddSlashes(pg_result($resaco,$conresaco,'y50_data'))."','$this->y50_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_hora"]))
           $resac = db_query("insert into db_acount values($acount,699,4987,'".AddSlashes(pg_result($resaco,$conresaco,'y50_hora'))."','$this->y50_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_obs"]))
           $resac = db_query("insert into db_acount values($acount,699,4988,'".AddSlashes(pg_result($resaco,$conresaco,'y50_obs'))."','$this->y50_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_setor"]))
           $resac = db_query("insert into db_acount values($acount,699,4989,'".AddSlashes(pg_result($resaco,$conresaco,'y50_setor'))."','$this->y50_setor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_nome"]))
           $resac = db_query("insert into db_acount values($acount,699,4990,'".AddSlashes(pg_result($resaco,$conresaco,'y50_nome'))."','$this->y50_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,699,4992,'".AddSlashes(pg_result($resaco,$conresaco,'y50_dtvenc'))."','$this->y50_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_numbloco"]))
           $resac = db_query("insert into db_acount values($acount,699,5137,'".AddSlashes(pg_result($resaco,$conresaco,'y50_numbloco'))."','$this->y50_numbloco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_prazorec"]))
           $resac = db_query("insert into db_acount values($acount,699,6788,'".AddSlashes(pg_result($resaco,$conresaco,'y50_prazorec'))."','$this->y50_prazorec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,699,6792,'".AddSlashes(pg_result($resaco,$conresaco,'y50_codtipo'))."','$this->y50_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y50_instit"]))
           $resac = db_query("insert into db_acount values($acount,699,10667,'".AddSlashes(pg_result($resaco,$conresaco,'y50_instit'))."','$this->y50_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "auto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y50_codauto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "auto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y50_codauto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y50_codauto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($y50_codauto=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y50_codauto));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4985,'$y50_codauto','E')");
         $resac = db_query("insert into db_acount values($acount,699,4985,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_codauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,4986,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,4987,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,4988,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,4989,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,4990,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,4992,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,5137,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_numbloco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,6788,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_prazorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,6792,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,699,10667,'','".AddSlashes(pg_result($resaco,$iresaco,'y50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from auto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y50_codauto != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y50_codauto = $y50_codauto ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "auto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y50_codauto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "auto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y50_codauto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y50_codauto;
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
        $this->erro_sql   = "Record Vazio na Tabela:auto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_calculo ( $y50_codauto=""){
   $result =  db_query("select fc_autodeinfracao($y50_codauto)");
   return $result;
  }
   function sql_query ( $y50_codauto=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from auto ";
     $sql .= "      inner join db_config  on  db_config.codigo = auto.y50_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = auto.y50_setor";
     $sql .= "      inner join tipofiscaliza  on  tipofiscaliza.y27_codtipo = auto.y50_codtipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($y50_codauto!=null ){
         $sql2 .= " where auto.y50_codauto = $y50_codauto ";
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
   function sql_query_busca($y50_codauto=null,$dbwhere=""){
 $sql = "select dl_Auto,dl_identificacao,dl_codigo,z01_nome,tipo,y50_instit,y50_numbloco  from
                        (select
												   y50_numbloco,
												   y50_instit,
												   y50_setor,
                           y50_codauto as dl_Auto,
                           case when q02_numcgm is not null then 'Inscrição'  else
                                 (case when j01_numcgm is not null then 'Matrícula' else
                                        (case when y80_numcgm is not null then 'Sanitário '  else
                                                (case when z01_numcgm is not null then 'Cgm' else
                                                     (case when y30_codnoti is not null then 'Notificação' else 'Nenhum'
                                                      end)
                                                end)
                                        end )
                                end )
                        end as dl_identificacao,
                        case when y52_inscr is not null then y52_inscr else
                                (case when y53_matric is not null then y53_matric else
                                        (case when y55_codsani is not null then y55_codsani else
                                                (case when z01_numcgm is not null then z01_numcgm else
                                                     (case when y51_codnoti is not null then y51_codnoti
                                                      end)
                                                end)
                                        end )
                                end )
                        end as dl_codigo,
                        case when q02_numcgm is not null then q02_numcgm else
                                (case when j01_numcgm is not null then j01_numcgm else
                                        (case when y80_numcgm is not null then y80_numcgm else
                                                (case when z01_numcgm is not null then z01_numcgm else q02_numcgm
                                                end)
                                        end )
                                end )
                        end as z01_numcgm ,
                        y27_descr as tipo
                from auto
                        left join tipofiscaliza on y50_codtipo=y27_codtipo
                        left join autocgm on y54_codauto = y50_codauto
                        left join autoinscr on y52_codauto = y50_codauto
                        left join automatric on y53_codauto = y50_codauto
                        left join autosanitario on y55_codauto = y50_codauto
                        left join iptubase on j01_matric = y53_matric
                        left join issbase on y52_inscr = q02_inscr
                        left join cgm on z01_numcgm = y54_numcgm
                        left join sanitario on y80_codsani = y55_codsani
                        left join autofiscal on y51_codauto = y50_codauto
                        left join fiscal on y51_codnoti = y30_codnoti
												) as x
                    inner join cgm on cgm.z01_numcgm = x.z01_numcgm";


     $sql2 = "";
     if($dbwhere==""){
       if($y50_codauto!=null ){
         $sql2 .= " where dl_auto = $y50_codauto ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;

     return $sql;
  }
   function sql_query_busca2($y50_codauto=null,$dbwhere=""){

     $sql = "select dl_Auto,dl_identifica,dl_codigo,z01_nome,tipo,y50_setor from
                        (select
			   y50_setor,
                           y50_codauto as dl_Auto,
			   case when q02_numcgm is not null then 'Inscrição'  else
                                 (case when j01_numcgm is not null then 'Matrícula' else
                                        (case when y80_numcgm is not null then 'Sanitário '  else
                                                (case when z01_numcgm is not null then 'Cgm' else
						     (case when y30_codnoti is not null then 'Notificação' else 'Nenhum'
						      end)
                                                end)
                                        end )
                                end )
                        end as dl_identifica,
                        case when y52_inscr is not null then y52_inscr else
                                (case when y53_matric is not null then y53_matric else
                                        (case when y55_codsani is not null then y55_codsani else
                                                (case when z01_numcgm is not null then z01_numcgm else
						     (case when y51_codnoti is not null then y51_codnoti
						      end)
                                                end)
                                        end )
                                end )
                        end as dl_codigo,
                        case when q02_numcgm is not null then q02_numcgm else
                                (case when j01_numcgm is not null then j01_numcgm else
                                        (case when y80_numcgm is not null then y80_numcgm else
                                                (case when z01_numcgm is not null then z01_numcgm else q02_numcgm
                                                end)
                                        end )
                                end )
                        end as z01_numcgm ,
			y27_descr as tipo,
      y50_instit
                from auto
		        left join tipofiscaliza on y50_codtipo=y27_codtipo
                        left join autocgm on y54_codauto = y50_codauto
                        left join autoinscr on y52_codauto = y50_codauto
                        left join automatric on y53_codauto = y50_codauto
                        left join autosanitario on y55_codauto = y50_codauto
                        left join iptubase on j01_matric = y53_matric
                        left join issbase on y52_inscr = q02_inscr
                        left join cgm on z01_numcgm = y54_numcgm
                        left join sanitario on y80_codsani = y55_codsani
                        left join autofiscal on y51_codauto = y50_codauto
                        left join fiscal on y51_codnoti = y30_codnoti
	        ) as x
		    inner join cgm on cgm.z01_numcgm=x.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($y50_codauto!=null ){
         $sql2 .= " where dl_auto = $y50_codauto ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;

     return $sql;
  }
   function sql_querycgm ($y50_codauto=null){
     $sql = "select
                        case when q02_numcgm is not null then q02_numcgm else
                                (case when j01_numcgm is not null then j01_numcgm else
                                        (case when y80_numcgm is not null then y80_numcgm else
                                                (case when z01_numcgm is not null then z01_numcgm else q02_numcgm
                                                end)
                                        end )
                                end )
                        end as z01_numcgm ,
                        case when y30_codnoti is not null then y30_codnoti
                        end as y30_codnoti
                from auto
                        left join autocgm on y54_codauto = y50_codauto
                        left join autoinscr on y52_codauto = y50_codauto
                        left join automatric on y53_codauto = y50_codauto
                        left join autosanitario on y55_codauto = y50_codauto
                        left join iptubase on j01_matric = y53_matric
                        left join issbase on y52_inscr = q02_inscr
                        left join cgm on z01_numcgm = y54_numcgm
                        left join sanitario on y80_codsani = y55_codsani
                        left join autofiscal on y51_codauto = y50_codauto
                        left join fiscal on y51_codnoti = y30_codnoti ";
     $sql2 = "";
     if($y50_codauto!=null ){
       $sql2 .= " where auto.y50_codauto = $y50_codauto ";
     }
     $sql .= $sql2;
     return $sql;
  }
   function sql_query_cgm ($y50_codauto=null,$dbwhere=""){

     $sql = "select * from (select
                        case when q02_numcgm is not null then q02_numcgm else
                                (case when j01_numcgm is not null then j01_numcgm else
                                        (case when y80_numcgm is not null then y80_numcgm else
                                                (case when z01_numcgm is not null then z01_numcgm else q02_numcgm
                                                end)
                                        end )
                                end )
                        end as z01_numcgm ,
                        case when y30_codnoti is not null then y30_codnoti
                        end as y30_codnoti,
                        y50_codauto
                from auto
                        left join autocgm on y54_codauto = y50_codauto
                        left join autoinscr on y52_codauto = y50_codauto
                        left join automatric on y53_codauto = y50_codauto
                        left join autosanitario on y55_codauto = y50_codauto
                        left join iptubase on j01_matric = y53_matric
                        left join issbase on y52_inscr = q02_inscr
                        left join cgm on z01_numcgm = y54_numcgm
                        left join sanitario on y80_codsani = y55_codsani
                        left join autofiscal on y51_codauto = y50_codauto
                        left join fiscal on y51_codnoti = y30_codnoti) as x ";
     $sql2 = "";
     if($dbwhere==""){
       if($y50_codauto!=null ){
         $sql2 .= " where auto.y50_codauto = $y50_codauto ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
 return $sql;
  }
   function sql_query_file ( $y50_codauto=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from auto ";
     $sql2 = "";
     if($dbwhere==""){
       if($y50_codauto!=null ){
         $sql2 .= " where auto.y50_codauto = $y50_codauto ";
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
   function sql_query_info ( $y50_codauto=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from auto ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = auto.y50_setor";
     $sql .= "      inner join tipofiscaliza  on  tipofiscaliza.y27_codtipo = auto.y50_codtipo";
     $sql .= "      left join autocgm on y54_codauto = y50_codauto";
     $sql .= "      left join autoinscr on y52_codauto = y50_codauto";
     $sql .= "      left join issbase on y52_inscr = q02_inscr";
     $sql .= "      left join automatric on y53_codauto = y50_codauto";
     $sql .= "      left join autosanitario on y55_codauto = y50_codauto";
     $sql .= "      left join sanitario on y80_codsani = y55_codsani";
     $sql .= "      left join autofiscal on y51_codauto = y50_codauto";
     $sql2 = "";
     if($dbwhere==""){
       if($y50_codauto!=null ){
         $sql2 .= " where auto.y50_codauto = $y50_codauto ";
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
   function sql_query_infoautos($y50_codauto=null,$dbwhere=""){

     $sql = "select auto,
                    identificacao,
                    codigo,
                    z01_nome as nome,
                    tipo,y50_data as data,
                    y50_setor as setor,
                    descrdepto,
                    y50_hora as hora,
                    z01_cgccpf as cpf,
                    j13_descr as bairro,
                    j14_nome as rua,
                    y14_numero as numero,
                    y14_compl as complemento,
                    y50_prazorec as prazo_recurso,
                    z01_incest as inscest,
                    y50_obs as obs
             from
                    (select y14_numero,
                            y14_compl,
                            j13_descr,
                            j14_nome,
                            y50_hora,
                            y50_prazorec,
                            y50_data,
                            y50_setor,
                            y50_obs,
                            y50_codauto as auto,
                            case when q02_numcgm is not null then 'Inscrição'  else
                                  (case when j01_numcgm is not null then 'Matrícula' else
                                         (case when y80_numcgm is not null then 'Sanitário '  else
                                                 (case when z01_numcgm is not null then 'Cgm' else
                                                      (case when y30_codnoti is not null then 'Notificação' else 'Nenhum'
                                                       end)
                                                 end)
                            end )
                                   end )
                            end as identificacao,
                            case when y52_inscr is not null then y52_inscr else
                                    (case when y53_matric is not null then y53_matric else
                                            (case when y55_codsani is not null then y55_codsani else
                                                    (case when z01_numcgm is not null then z01_numcgm else
                                                         (case when y51_codnoti is not null then y51_codnoti
                                                          end)
                                                    end)
                                            end )
                                    end )
                            end as codigo,
                            case when q02_numcgm is not null then q02_numcgm else
                                    (case when j01_numcgm is not null then j01_numcgm else
                                            (case when y80_numcgm is not null then y80_numcgm else
                                                    (case when z01_numcgm is not null then z01_numcgm else q02_numcgm
                                                    end)
                                            end )
                                    end )
                            end as z01_numcgm ,
                            y27_descr as tipo
                     from auto
                            inner join tipofiscaliza on y50_codtipo=y27_codtipo
                            left join autocgm on y54_codauto = y50_codauto
                            left join autoinscr on y52_codauto = y50_codauto
                            left join automatric on y53_codauto = y50_codauto
                            left join autosanitario on y55_codauto = y50_codauto
                            left join iptubase on j01_matric = y53_matric
                            left join issbase on y52_inscr = q02_inscr
                            left join cgm on z01_numcgm = y54_numcgm
                            left join sanitario on y80_codsani = y55_codsani
                            left join autofiscal on y51_codauto = y50_codauto
                            left join fiscal on y51_codnoti = y30_codnoti    inner join autolocal on y14_codauto = y50_codauto
                            left join ruas on j14_codigo = y14_codigo
                            left join bairro on j13_codi = y14_codi
                    ) as x
                    inner join db_depart on db_depart.coddepto = x.y50_setor
                    inner join cgm on cgm.z01_numcgm=x.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($y50_codauto!=null ){
         $sql2 .= " where auto = $y50_codauto ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;

     return $sql;
  }

  function  sql_query_cgm_inscricao($y50_codauto = null) {

    $sSql  = "select   ";
    $sSql .= "    y52_inscr as q02_inscr, ";
    $sSql .= "    case when y54_numcgm is not null then y54_numcgm ";
    $sSql .= "         when y80_numcgm is not null then y80_numcgm ";
    $sSql .= "         when j01_numcgm is not null then j01_numcgm ";
    $sSql .= "         else null      ";
    $sSql .= "      end as z01_numcgm, ";
    $sSql .= "    case when y54_numcgm is not null then cgmauto.z01_nome ";
    $sSql .= "         when y80_numcgm is not null then cgmsan.z01_nome  ";
    $sSql .= "         when j01_numcgm is not null then cgmiptu.z01_nome ";
    $sSql .= "         when q02_numcgm is not null then cgmiss.z01_nome  ";
    $sSql .= "         else null            ";
    $sSql .= "      end as z01_nomecgminscr ";
    $sSql .= "from auto                     ";
    $sSql .= "    left join autocgm        on y54_codauto         = y50_codauto ";
    $sSql .= "    left join cgm as cgmauto on cgmauto.z01_numcgm  = y54_numcgm  ";
    $sSql .= "    left join autoinscr      on y52_codauto         = y50_codauto ";
    $sSql .= "    left join issbase        on q02_inscr           = y52_inscr   ";
    $sSql .= "    left join cgm as cgmiss  on cgmiss.z01_numcgm   = q02_numcgm  ";
    $sSql .= "    left join autosanitario  on y55_codauto         = y50_codauto ";
    $sSql .= "    left join sanitario      on y80_codsani         = y55_codsani ";
    $sSql .= "    left join cgm as cgmsan  on cgmsan.z01_numcgm   = y80_numcgm  ";
    $sSql .= "    left join automatric     on y53_codauto         = y50_codauto ";
    $sSql .= "    left join iptubase       on j01_matric          = y53_matric  ";
    $sSql .= "    left join cgm as cgmiptu on cgmiptu.z01_numcgm  = j01_numcgm  ";

    if (!is_null($y50_codauto)) {
      $sSql .= " where y50_codauto = $y50_codauto ";
    }

    return $sSql;
  }

}
?>