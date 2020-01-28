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

//MODULO: biblioteca
//CLASSE DA ENTIDADE reserva
class cl_reserva {
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
   var $bi14_codigo = 0;
   var $bi14_carteira = 0;
   var $bi14_usuario = 0;
   var $bi14_acervo = 0;
   var $bi14_data_dia = null;
   var $bi14_data_mes = null;
   var $bi14_data_ano = null;
   var $bi14_data = null;
   var $bi14_datareserva_dia = null;
   var $bi14_datareserva_mes = null;
   var $bi14_datareserva_ano = null;
   var $bi14_datareserva = null;
   var $bi14_hora = null;
   var $bi14_retirada_dia = null;
   var $bi14_retirada_mes = null;
   var $bi14_retirada_ano = null;
   var $bi14_retirada = null;
   var $bi14_situacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 bi14_codigo = int8 = Código da Reserva
                 bi14_carteira = int8 = Carteira
                 bi14_usuario = int8 = Usuário
                 bi14_acervo = int8 = Código do Acervo
                 bi14_data = date = Data
                 bi14_datareserva = date = Reservar para
                 bi14_hora = char(5) = Hora da Reserva
                 bi14_retirada = date = Data da Retirada
                 bi14_situacao = char(1) = Situação
                 ";
   //funcao construtor da classe
   function cl_reserva() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("reserva");
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
       $this->bi14_codigo = ($this->bi14_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_codigo"]:$this->bi14_codigo);
       $this->bi14_carteira = ($this->bi14_carteira == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_carteira"]:$this->bi14_carteira);
       $this->bi14_usuario = ($this->bi14_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_usuario"]:$this->bi14_usuario);
       $this->bi14_acervo = ($this->bi14_acervo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_acervo"]:$this->bi14_acervo);
       if($this->bi14_data == ""){
         $this->bi14_data_dia = ($this->bi14_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_data_dia"]:$this->bi14_data_dia);
         $this->bi14_data_mes = ($this->bi14_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_data_mes"]:$this->bi14_data_mes);
         $this->bi14_data_ano = ($this->bi14_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_data_ano"]:$this->bi14_data_ano);
         if($this->bi14_data_dia != ""){
            $this->bi14_data = $this->bi14_data_ano."-".$this->bi14_data_mes."-".$this->bi14_data_dia;
         }
       }
       if($this->bi14_datareserva == ""){
         $this->bi14_datareserva_dia = ($this->bi14_datareserva_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_datareserva_dia"]:$this->bi14_datareserva_dia);
         $this->bi14_datareserva_mes = ($this->bi14_datareserva_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_datareserva_mes"]:$this->bi14_datareserva_mes);
         $this->bi14_datareserva_ano = ($this->bi14_datareserva_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_datareserva_ano"]:$this->bi14_datareserva_ano);
         if($this->bi14_datareserva_dia != ""){
            $this->bi14_datareserva = $this->bi14_datareserva_ano."-".$this->bi14_datareserva_mes."-".$this->bi14_datareserva_dia;
         }
       }
       $this->bi14_hora = ($this->bi14_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_hora"]:$this->bi14_hora);
       if($this->bi14_retirada == ""){
         $this->bi14_retirada_dia = ($this->bi14_retirada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_retirada_dia"]:$this->bi14_retirada_dia);
         $this->bi14_retirada_mes = ($this->bi14_retirada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_retirada_mes"]:$this->bi14_retirada_mes);
         $this->bi14_retirada_ano = ($this->bi14_retirada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_retirada_ano"]:$this->bi14_retirada_ano);
         if($this->bi14_retirada_dia != ""){
            $this->bi14_retirada = $this->bi14_retirada_ano."-".$this->bi14_retirada_mes."-".$this->bi14_retirada_dia;
         }
       }
       $this->bi14_situacao = ($this->bi14_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_situacao"]:$this->bi14_situacao);
     }else{
       $this->bi14_codigo = ($this->bi14_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi14_codigo"]:$this->bi14_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi14_codigo){
      $this->atualizacampos();
     if($this->bi14_carteira == null ){
       $this->erro_sql = " Campo Carteira nao Informado.";
       $this->erro_campo = "bi14_carteira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi14_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "bi14_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi14_acervo == null ){
       $this->erro_sql = " Campo Código do Acervo nao Informado.";
       $this->erro_campo = "bi14_acervo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi14_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "bi14_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi14_datareserva == null ){
       $this->erro_sql = " Campo Reservar para nao Informado.";
       $this->erro_campo = "bi14_datareserva_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi14_hora == null ){
       $this->erro_sql = " Campo Hora da Reserva nao Informado.";
       $this->erro_campo = "bi14_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi14_retirada == null ){
       $this->bi14_retirada = "null";
     }
     if($bi14_codigo == "" || $bi14_codigo == null ){
       $result = db_query("select nextval('reserva_bi14_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: reserva_bi14_codigo_seq do campo: bi14_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->bi14_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from reserva_bi14_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi14_codigo)){
         $this->erro_sql = " Campo bi14_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi14_codigo = $bi14_codigo;
       }
     }
     if(($this->bi14_codigo == null) || ($this->bi14_codigo == "") ){
       $this->erro_sql = " Campo bi14_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into reserva(
                                       bi14_codigo
                                      ,bi14_carteira
                                      ,bi14_usuario
                                      ,bi14_acervo
                                      ,bi14_data
                                      ,bi14_datareserva
                                      ,bi14_hora
                                      ,bi14_retirada
                                      ,bi14_situacao
                       )
                values (
                                $this->bi14_codigo
                               ,$this->bi14_carteira
                               ,$this->bi14_usuario
                               ,$this->bi14_acervo
                               ,".($this->bi14_data == "null" || $this->bi14_data == ""?"null":"'".$this->bi14_data."'")."
                               ,".($this->bi14_datareserva == "null" || $this->bi14_datareserva == ""?"null":"'".$this->bi14_datareserva."'")."
                               ,'$this->bi14_hora'
                               ,".($this->bi14_retirada == "null" || $this->bi14_retirada == ""?"null":"'".$this->bi14_retirada."'")."
                               ,'$this->bi14_situacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Reservas de Acervos ($this->bi14_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Reservas de Acervos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Reservas de Acervos ($this->bi14_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi14_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi14_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008920,'$this->bi14_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010150,1008920,'','".AddSlashes(pg_result($resaco,0,'bi14_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010150,1008921,'','".AddSlashes(pg_result($resaco,0,'bi14_carteira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010150,1008936,'','".AddSlashes(pg_result($resaco,0,'bi14_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010150,1008949,'','".AddSlashes(pg_result($resaco,0,'bi14_acervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010150,1008922,'','".AddSlashes(pg_result($resaco,0,'bi14_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010150,1008950,'','".AddSlashes(pg_result($resaco,0,'bi14_datareserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010150,1008923,'','".AddSlashes(pg_result($resaco,0,'bi14_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010150,1008924,'','".AddSlashes(pg_result($resaco,0,'bi14_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010150,1008925,'','".AddSlashes(pg_result($resaco,0,'bi14_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($bi14_codigo=null) {
      $this->atualizacampos();
     $sql = " update reserva set ";
     $virgula = "";
     if(trim($this->bi14_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_codigo"])){
       $sql  .= $virgula." bi14_codigo = $this->bi14_codigo ";
       $virgula = ",";
       if(trim($this->bi14_codigo) == null ){
         $this->erro_sql = " Campo Código da Reserva nao Informado.";
         $this->erro_campo = "bi14_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi14_carteira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_carteira"])){
       $sql  .= $virgula." bi14_carteira = $this->bi14_carteira ";
       $virgula = ",";
       if(trim($this->bi14_carteira) == null ){
         $this->erro_sql = " Campo Carteira nao Informado.";
         $this->erro_campo = "bi14_carteira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi14_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_usuario"])){
       $sql  .= $virgula." bi14_usuario = $this->bi14_usuario ";
       $virgula = ",";
       if(trim($this->bi14_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "bi14_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi14_acervo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_acervo"])){
       $sql  .= $virgula." bi14_acervo = $this->bi14_acervo ";
       $virgula = ",";
       if(trim($this->bi14_acervo) == null ){
         $this->erro_sql = " Campo Código do Acervo nao Informado.";
         $this->erro_campo = "bi14_acervo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi14_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi14_data_dia"] !="") ){
       $sql  .= $virgula." bi14_data = '$this->bi14_data' ";
       $virgula = ",";
       if(trim($this->bi14_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "bi14_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_data_dia"])){
         $sql  .= $virgula." bi14_data = null ";
         $virgula = ",";
         if(trim($this->bi14_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "bi14_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi14_datareserva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_datareserva_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi14_datareserva_dia"] !="") ){
       $sql  .= $virgula." bi14_datareserva = '$this->bi14_datareserva' ";
       $virgula = ",";
       if(trim($this->bi14_datareserva) == null ){
         $this->erro_sql = " Campo Reservar para nao Informado.";
         $this->erro_campo = "bi14_datareserva_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_datareserva_dia"])){
         $sql  .= $virgula." bi14_datareserva = null ";
         $virgula = ",";
         if(trim($this->bi14_datareserva) == null ){
           $this->erro_sql = " Campo Reservar para nao Informado.";
           $this->erro_campo = "bi14_datareserva_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi14_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_hora"])){
       $sql  .= $virgula." bi14_hora = '$this->bi14_hora' ";
       $virgula = ",";
       if(trim($this->bi14_hora) == null ){
         $this->erro_sql = " Campo Hora da Reserva nao Informado.";
         $this->erro_campo = "bi14_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi14_retirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_retirada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi14_retirada_dia"] !="") ){
       $sql  .= $virgula." bi14_retirada = '$this->bi14_retirada' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_retirada_dia"])){
         $sql  .= $virgula." bi14_retirada = null ";
         $virgula = ",";
       }
     }
     if(trim($this->bi14_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi14_situacao"])){
       $sql  .= $virgula." bi14_situacao = '$this->bi14_situacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($bi14_codigo!=null){
       $sql .= " bi14_codigo = $this->bi14_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi14_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008920,'$this->bi14_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008920,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_codigo'))."','$this->bi14_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_carteira"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008921,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_carteira'))."','$this->bi14_carteira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008936,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_usuario'))."','$this->bi14_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_acervo"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008949,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_acervo'))."','$this->bi14_acervo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_data"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008922,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_data'))."','$this->bi14_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_datareserva"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008950,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_datareserva'))."','$this->bi14_datareserva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_hora"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008923,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_hora'))."','$this->bi14_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_retirada"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008924,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_retirada'))."','$this->bi14_retirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi14_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1010150,1008925,'".AddSlashes(pg_result($resaco,$conresaco,'bi14_situacao'))."','$this->bi14_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reservas de Acervos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi14_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reservas de Acervos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi14_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi14_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($bi14_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi14_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008920,'$bi14_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010150,1008920,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010150,1008921,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_carteira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010150,1008936,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010150,1008949,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_acervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010150,1008922,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010150,1008950,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_datareserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010150,1008923,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010150,1008924,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010150,1008925,'','".AddSlashes(pg_result($resaco,$iresaco,'bi14_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from reserva
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi14_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi14_codigo = $bi14_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reservas de Acervos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi14_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reservas de Acervos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi14_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi14_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:reserva";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bi14_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from reserva ";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = reserva.bi14_acervo";
     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = reserva.bi14_usuario";
     $sql .= "      inner join carteira  on  carteira.bi16_codigo = reserva.bi14_carteira";
     $sql .= "      inner join leitorcategoria  on  leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = leitorcategoria.bi07_biblioteca";
     $sql .= "      inner join leitor  on  leitor.bi10_codigo = carteira.bi16_leitor";
     $sql .= "      left join leitoraluno on leitoraluno.bi11_leitor = leitor.bi10_codigo";
     $sql .= "      left join aluno on aluno.ed47_i_codigo = leitoraluno.bi11_aluno";
     $sql .= "      left join alunocurso on alunocurso.ed56_i_aluno = ed47_i_codigo";
     $sql .= "      left join leitorfunc on leitorfunc.bi12_leitor = leitor.bi10_codigo";
     $sql .= "      left join rechumano on rechumano.ed20_i_codigo = leitorfunc.bi12_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      left join leitorpublico on leitorpublico.bi13_leitor = leitor.bi10_codigo";
     $sql .= "      left join cgm as cgmpub on cgmpub.z01_numcgm = leitorpublico.bi13_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($bi14_codigo!=null ){
         $sql2 .= " where reserva.bi14_codigo = $bi14_codigo ";
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
   function sql_query_file ( $bi14_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from reserva ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi14_codigo!=null ){
         $sql2 .= " where reserva.bi14_codigo = $bi14_codigo ";
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

  function sql_query_acervo_leitorcidadao ( $bi14_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from reserva ";
    $sql .= "      inner join acervo           on  acervo.bi06_seq             = reserva.bi14_acervo                   \n";
    $sql .= "      inner join editora          on  editora.bi02_codigo         = acervo.bi06_editora                   \n";
    $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria           \n";
    $sql .= "      inner join tipoitem         on  tipoitem.bi05_codigo        = acervo.bi06_tipoitem                  \n";
    $sql .= "      inner join db_usuarios      on  db_usuarios.id_usuario      = reserva.bi14_usuario                  \n";
    $sql .= "      inner join carteira         on  carteira.bi16_codigo        = reserva.bi14_carteira                 \n";
    $sql .= "      inner join leitorcategoria  on  leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria         \n";
    $sql .= "      inner join biblioteca       on  biblioteca.bi17_codigo      = leitorcategoria.bi07_biblioteca       \n";
    $sql .= "      inner join leitor           on  leitor.bi10_codigo          = carteira.bi16_leitor                  \n";
    $sql .= "      left  join leitorcidadao    on leitorcidadao.bi28_leitor    = leitor.bi10_codigo                    \n";
    $sql .= "      left  join cidadao          on cidadao.ov02_sequencial      = leitorcidadao.bi28_cidadao_sequencial \n";
    $sql .= "                                 and cidadao.ov02_seq             = leitorcidadao.bi28_cidadao_seq        \n";
    $sql2 = "";

    if ($dbwhere == "") {

      if ($bi14_codigo != null ) {
        $sql2 .= " where reserva.bi14_codigo = $bi14_codigo ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }

  public function sql_query_reserva( $bi14_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql  = "select {$campos} ";
    $sql .= "  from reserva ";
    $sql .= "      inner join acervo    on acervo.bi06_seq      = reserva.bi14_acervo ";
    $sql .= "      inner join exemplar  on exemplar.bi23_acervo = acervo.bi06_seq ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($bi14_codigo)){
        $sql2 .= " where reserva.bi14_codigo = $bi14_codigo ";
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
?>