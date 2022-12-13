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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_prestadorhorarios
class cl_sau_prestadorhorarios {
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
   var $s112_i_codigo = 0;
   var $s112_i_prestadorvinc = 0;
   var $s112_i_diasemana = 0;
   var $s112_c_horaini = null;
   var $s112_c_horafim = null;
   var $s112_i_fichas = 0;
   var $s112_i_reservas = 0;
   var $s112_c_tipograde = null;
   var $s112_i_tipoficha = 0;
   var $s112_d_valinicial_dia = null;
   var $s112_d_valinicial_mes = null;
   var $s112_d_valinicial_ano = null;
   var $s112_d_valinicial = null;
   var $s112_d_valfinal_dia = null;
   var $s112_d_valfinal_mes = null;
   var $s112_d_valfinal_ano = null;
   var $s112_d_valfinal = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 s112_i_codigo = int4 = Código
                 s112_i_prestadorvinc = int4 = Prestador Vinculo
                 s112_i_diasemana = int4 = Dia da Semana
                 s112_c_horaini = char(5) = Hora Inicial
                 s112_c_horafim = char(5) = Hora Final
                 s112_i_fichas = int4 = Fichas
                 s112_i_reservas = int4 = Reservas
                 s112_c_tipograde = char(1) = Tipo Grade
                 s112_i_tipoficha = int4 = Tipo Ficha
                 s112_d_valinicial = date = Validade Inicial
                 s112_d_valfinal = date = Validade Final
                 ";
   //funcao construtor da classe
   function cl_sau_prestadorhorarios() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_prestadorhorarios");
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
       $this->s112_i_codigo = ($this->s112_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_i_codigo"]:$this->s112_i_codigo);
       $this->s112_i_prestadorvinc = ($this->s112_i_prestadorvinc == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_i_prestadorvinc"]:$this->s112_i_prestadorvinc);
       $this->s112_i_diasemana = ($this->s112_i_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_i_diasemana"]:$this->s112_i_diasemana);
       $this->s112_c_horaini = ($this->s112_c_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_c_horaini"]:$this->s112_c_horaini);
       $this->s112_c_horafim = ($this->s112_c_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_c_horafim"]:$this->s112_c_horafim);
       $this->s112_i_fichas = ($this->s112_i_fichas == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_i_fichas"]:$this->s112_i_fichas);
       $this->s112_i_reservas = ($this->s112_i_reservas == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_i_reservas"]:$this->s112_i_reservas);
       $this->s112_c_tipograde = ($this->s112_c_tipograde == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_c_tipograde"]:$this->s112_c_tipograde);
       $this->s112_i_tipoficha = ($this->s112_i_tipoficha == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_i_tipoficha"]:$this->s112_i_tipoficha);
       if($this->s112_d_valinicial == ""){
         $this->s112_d_valinicial_dia = ($this->s112_d_valinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_d_valinicial_dia"]:$this->s112_d_valinicial_dia);
         $this->s112_d_valinicial_mes = ($this->s112_d_valinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_d_valinicial_mes"]:$this->s112_d_valinicial_mes);
         $this->s112_d_valinicial_ano = ($this->s112_d_valinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_d_valinicial_ano"]:$this->s112_d_valinicial_ano);
         if($this->s112_d_valinicial_dia != ""){
            $this->s112_d_valinicial = $this->s112_d_valinicial_ano."-".$this->s112_d_valinicial_mes."-".$this->s112_d_valinicial_dia;
         }
       }
       if($this->s112_d_valfinal == ""){
         $this->s112_d_valfinal_dia = ($this->s112_d_valfinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_d_valfinal_dia"]:$this->s112_d_valfinal_dia);
         $this->s112_d_valfinal_mes = ($this->s112_d_valfinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_d_valfinal_mes"]:$this->s112_d_valfinal_mes);
         $this->s112_d_valfinal_ano = ($this->s112_d_valfinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_d_valfinal_ano"]:$this->s112_d_valfinal_ano);
         if($this->s112_d_valfinal_dia != ""){
            $this->s112_d_valfinal = $this->s112_d_valfinal_ano."-".$this->s112_d_valfinal_mes."-".$this->s112_d_valfinal_dia;
         }
       }
     }else{
       $this->s112_i_codigo = ($this->s112_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s112_i_codigo"]:$this->s112_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($s112_i_codigo){
      $this->atualizacampos();
     if($this->s112_i_prestadorvinc == null ){
       $this->erro_sql = " Campo Prestador Vinculo não informado.";
       $this->erro_campo = "s112_i_prestadorvinc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s112_i_diasemana == null ){
       $this->erro_sql = " Campo Dia da Semana não informado.";
       $this->erro_campo = "s112_i_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s112_c_horaini == null ){
       $this->erro_sql = " Campo Hora Inicial não informado.";
       $this->erro_campo = "s112_c_horaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s112_c_horafim == null ){
       $this->erro_sql = " Campo Hora Final não informado.";
       $this->erro_campo = "s112_c_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s112_i_fichas == null ){
       $this->erro_sql = " Campo Fichas não informado.";
       $this->erro_campo = "s112_i_fichas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s112_i_reservas == null ){
       $this->erro_sql = " Campo Reservas não informado.";
       $this->erro_campo = "s112_i_reservas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s112_c_tipograde == null ){
       $this->erro_sql = " Campo Tipo Grade não informado.";
       $this->erro_campo = "s112_c_tipograde";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s112_i_tipoficha == null ){
       $this->erro_sql = " Campo Tipo Ficha não informado.";
       $this->erro_campo = "s112_i_tipoficha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s112_d_valinicial == null ){
       $this->s112_d_valinicial = "null";
     }
     if($this->s112_d_valfinal == null ){
       $this->s112_d_valfinal = "null";
     }
     if($s112_i_codigo == "" || $s112_i_codigo == null ){
       $result = db_query("select nextval('sau_prestadorhorarios_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_prestadorhorarios_codigo_seq do campo: s112_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->s112_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sau_prestadorhorarios_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s112_i_codigo)){
         $this->erro_sql = " Campo s112_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s112_i_codigo = $s112_i_codigo;
       }
     }
     if(($this->s112_i_codigo == null) || ($this->s112_i_codigo == "") ){
       $this->erro_sql = " Campo s112_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_prestadorhorarios(
                                       s112_i_codigo
                                      ,s112_i_prestadorvinc
                                      ,s112_i_diasemana
                                      ,s112_c_horaini
                                      ,s112_c_horafim
                                      ,s112_i_fichas
                                      ,s112_i_reservas
                                      ,s112_c_tipograde
                                      ,s112_i_tipoficha
                                      ,s112_d_valinicial
                                      ,s112_d_valfinal
                       )
                values (
                                $this->s112_i_codigo
                               ,$this->s112_i_prestadorvinc
                               ,$this->s112_i_diasemana
                               ,'$this->s112_c_horaini'
                               ,'$this->s112_c_horafim'
                               ,$this->s112_i_fichas
                               ,$this->s112_i_reservas
                               ,'$this->s112_c_tipograde'
                               ,$this->s112_i_tipoficha
                               ,".($this->s112_d_valinicial == "null" || $this->s112_d_valinicial == ""?"null":"'".$this->s112_d_valinicial."'")."
                               ,".($this->s112_d_valfinal == "null" || $this->s112_d_valfinal == ""?"null":"'".$this->s112_d_valfinal."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prestador Horários ($this->s112_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prestador Horários já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prestador Horários ($this->s112_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s112_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s112_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13576,'$this->s112_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2375,13576,'','".AddSlashes(pg_result($resaco,0,'s112_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13577,'','".AddSlashes(pg_result($resaco,0,'s112_i_prestadorvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13578,'','".AddSlashes(pg_result($resaco,0,'s112_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13579,'','".AddSlashes(pg_result($resaco,0,'s112_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13580,'','".AddSlashes(pg_result($resaco,0,'s112_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13581,'','".AddSlashes(pg_result($resaco,0,'s112_i_fichas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13582,'','".AddSlashes(pg_result($resaco,0,'s112_i_reservas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13583,'','".AddSlashes(pg_result($resaco,0,'s112_c_tipograde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13584,'','".AddSlashes(pg_result($resaco,0,'s112_i_tipoficha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13585,'','".AddSlashes(pg_result($resaco,0,'s112_d_valinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2375,13586,'','".AddSlashes(pg_result($resaco,0,'s112_d_valfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($s112_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sau_prestadorhorarios set ";
     $virgula = "";
     if(trim($this->s112_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_i_codigo"])){
       $sql  .= $virgula." s112_i_codigo = $this->s112_i_codigo ";
       $virgula = ",";
       if(trim($this->s112_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "s112_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_i_prestadorvinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_i_prestadorvinc"])){
       $sql  .= $virgula." s112_i_prestadorvinc = $this->s112_i_prestadorvinc ";
       $virgula = ",";
       if(trim($this->s112_i_prestadorvinc) == null ){
         $this->erro_sql = " Campo Prestador Vinculo não informado.";
         $this->erro_campo = "s112_i_prestadorvinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_i_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_i_diasemana"])){
       $sql  .= $virgula." s112_i_diasemana = $this->s112_i_diasemana ";
       $virgula = ",";
       if(trim($this->s112_i_diasemana) == null ){
         $this->erro_sql = " Campo Dia da Semana não informado.";
         $this->erro_campo = "s112_i_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_c_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_c_horaini"])){
       $sql  .= $virgula." s112_c_horaini = '$this->s112_c_horaini' ";
       $virgula = ",";
       if(trim($this->s112_c_horaini) == null ){
         $this->erro_sql = " Campo Hora Inicial não informado.";
         $this->erro_campo = "s112_c_horaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_c_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_c_horafim"])){
       $sql  .= $virgula." s112_c_horafim = '$this->s112_c_horafim' ";
       $virgula = ",";
       if(trim($this->s112_c_horafim) == null ){
         $this->erro_sql = " Campo Hora Final não informado.";
         $this->erro_campo = "s112_c_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_i_fichas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_i_fichas"])){
       $sql  .= $virgula." s112_i_fichas = $this->s112_i_fichas ";
       $virgula = ",";
       if(trim($this->s112_i_fichas) == null ){
         $this->erro_sql = " Campo Fichas não informado.";
         $this->erro_campo = "s112_i_fichas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_i_reservas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_i_reservas"])){
       $sql  .= $virgula." s112_i_reservas = $this->s112_i_reservas ";
       $virgula = ",";
       if(trim($this->s112_i_reservas) == null ){
         $this->erro_sql = " Campo Reservas não informado.";
         $this->erro_campo = "s112_i_reservas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_c_tipograde)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_c_tipograde"])){
       $sql  .= $virgula." s112_c_tipograde = '$this->s112_c_tipograde' ";
       $virgula = ",";
       if(trim($this->s112_c_tipograde) == null ){
         $this->erro_sql = " Campo Tipo Grade não informado.";
         $this->erro_campo = "s112_c_tipograde";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_i_tipoficha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_i_tipoficha"])){
       $sql  .= $virgula." s112_i_tipoficha = $this->s112_i_tipoficha ";
       $virgula = ",";
       if(trim($this->s112_i_tipoficha) == null ){
         $this->erro_sql = " Campo Tipo Ficha não informado.";
         $this->erro_campo = "s112_i_tipoficha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s112_d_valinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_d_valinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s112_d_valinicial_dia"] !="") ){
       $sql  .= $virgula." s112_d_valinicial = '$this->s112_d_valinicial' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["s112_d_valinicial_dia"])){
         $sql  .= $virgula." s112_d_valinicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->s112_d_valfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s112_d_valfinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s112_d_valfinal_dia"] !="") ){
       $sql  .= $virgula." s112_d_valfinal = '$this->s112_d_valfinal' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["s112_d_valfinal_dia"])){
         $sql  .= $virgula." s112_d_valfinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($s112_i_codigo!=null){
       $sql .= " s112_i_codigo = $this->s112_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s112_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,13576,'$this->s112_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_i_codigo"]) || $this->s112_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2375,13576,'".AddSlashes(pg_result($resaco,$conresaco,'s112_i_codigo'))."','$this->s112_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_i_prestadorvinc"]) || $this->s112_i_prestadorvinc != "")
             $resac = db_query("insert into db_acount values($acount,2375,13577,'".AddSlashes(pg_result($resaco,$conresaco,'s112_i_prestadorvinc'))."','$this->s112_i_prestadorvinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_i_diasemana"]) || $this->s112_i_diasemana != "")
             $resac = db_query("insert into db_acount values($acount,2375,13578,'".AddSlashes(pg_result($resaco,$conresaco,'s112_i_diasemana'))."','$this->s112_i_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_c_horaini"]) || $this->s112_c_horaini != "")
             $resac = db_query("insert into db_acount values($acount,2375,13579,'".AddSlashes(pg_result($resaco,$conresaco,'s112_c_horaini'))."','$this->s112_c_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_c_horafim"]) || $this->s112_c_horafim != "")
             $resac = db_query("insert into db_acount values($acount,2375,13580,'".AddSlashes(pg_result($resaco,$conresaco,'s112_c_horafim'))."','$this->s112_c_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_i_fichas"]) || $this->s112_i_fichas != "")
             $resac = db_query("insert into db_acount values($acount,2375,13581,'".AddSlashes(pg_result($resaco,$conresaco,'s112_i_fichas'))."','$this->s112_i_fichas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_i_reservas"]) || $this->s112_i_reservas != "")
             $resac = db_query("insert into db_acount values($acount,2375,13582,'".AddSlashes(pg_result($resaco,$conresaco,'s112_i_reservas'))."','$this->s112_i_reservas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_c_tipograde"]) || $this->s112_c_tipograde != "")
             $resac = db_query("insert into db_acount values($acount,2375,13583,'".AddSlashes(pg_result($resaco,$conresaco,'s112_c_tipograde'))."','$this->s112_c_tipograde',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_i_tipoficha"]) || $this->s112_i_tipoficha != "")
             $resac = db_query("insert into db_acount values($acount,2375,13584,'".AddSlashes(pg_result($resaco,$conresaco,'s112_i_tipoficha'))."','$this->s112_i_tipoficha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_d_valinicial"]) || $this->s112_d_valinicial != "")
             $resac = db_query("insert into db_acount values($acount,2375,13585,'".AddSlashes(pg_result($resaco,$conresaco,'s112_d_valinicial'))."','$this->s112_d_valinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s112_d_valfinal"]) || $this->s112_d_valfinal != "")
             $resac = db_query("insert into db_acount values($acount,2375,13586,'".AddSlashes(pg_result($resaco,$conresaco,'s112_d_valfinal'))."','$this->s112_d_valfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prestador Horários não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s112_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prestador Horários não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s112_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s112_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($s112_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($s112_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,13576,'$s112_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2375,13576,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13577,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_i_prestadorvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13578,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13579,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13580,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13581,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_i_fichas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13582,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_i_reservas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13583,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_c_tipograde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13584,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_i_tipoficha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13585,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_d_valinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2375,13586,'','".AddSlashes(pg_result($resaco,$iresaco,'s112_d_valfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sau_prestadorhorarios
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($s112_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " s112_i_codigo = $s112_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prestador Horários não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s112_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prestador Horários não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s112_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s112_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_prestadorhorarios";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($s112_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from sau_prestadorhorarios ";
     $sql .= "      inner join sau_tipoficha  on  sau_tipoficha.sd101_i_codigo = sau_prestadorhorarios.s112_i_tipoficha";
     $sql .= "      inner join sau_prestadorvinculos  on  sau_prestadorvinculos.s111_i_codigo = sau_prestadorhorarios.s112_i_prestadorvinc";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = sau_prestadorhorarios.s112_i_diasemana";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = sau_prestadorvinculos.s111_procedimento";
     $sql .= "      inner join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_prestadorvinculos.s111_i_prestador";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($s112_i_codigo)) {
         $sql2 .= " where sau_prestadorhorarios.s112_i_codigo = $s112_i_codigo ";
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
  public function sql_query_file ($s112_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from sau_prestadorhorarios ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($s112_i_codigo)){
        $sql2 .= " where sau_prestadorhorarios.s112_i_codigo = $s112_i_codigo ";
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

  public function sql_query_existe_agenda ($s112_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = " select {$campos} ";
    $sql .= "   from sau_prestadorhorarios ";
    $sql .= "  inner join sau_prestadorvinculos on sau_prestadorvinculos.s111_i_codigo       = sau_prestadorhorarios.s112_i_prestadorvinc ";
    $sql .= "  inner join sau_agendaexames      on sau_agendaexames.s113_i_prestadorhorarios = sau_prestadorhorarios.s112_i_codigo ";
    $sql2 = "";
    if (empty($dbwhere)) {

      if (!empty($s112_i_codigo)){
        $sql2 .= " where sau_prestadorhorarios.s112_i_codigo = $s112_i_codigo ";
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

   public function sql_query_grupo ($s112_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from sau_prestadorhorarios ";
     $sql .= "      inner join sau_tipoficha  on  sau_tipoficha.sd101_i_codigo = sau_prestadorhorarios.s112_i_tipoficha";
     $sql .= "      inner join sau_prestadorvinculos  on  sau_prestadorvinculos.s111_i_codigo = sau_prestadorhorarios.s112_i_prestadorvinc";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = sau_prestadorhorarios.s112_i_diasemana";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = sau_prestadorvinculos.s111_procedimento";
     $sql .= "      inner join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_prestadorvinculos.s111_i_prestador";
     $sql .= "      inner join grupoexameprestador  on  age03_prestadorvinculos = s111_i_codigo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($s112_i_codigo)) {
         $sql2 .= " where sau_prestadorhorarios.s112_i_codigo = $s112_i_codigo ";
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
