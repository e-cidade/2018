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

//MODULO: empenho
//CLASSE DA ENTIDADE empautoriza
class cl_empautoriza {
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
   var $e54_autori = 0;
   var $e54_numcgm = 0;
   var $e54_login = 0;
   var $e54_codcom = 0;
   var $e54_destin = null;
   var $e54_valor = 0;
   var $e54_anousu = 0;
   var $e54_tipol = null;
   var $e54_numerl = null;
   var $e54_praent = null;
   var $e54_entpar = null;
   var $e54_conpag = null;
   var $e54_codout = null;
   var $e54_contat = null;
   var $e54_telef = null;
   var $e54_numsol = 0;
   var $e54_anulad_dia = null;
   var $e54_anulad_mes = null;
   var $e54_anulad_ano = null;
   var $e54_anulad = null;
   var $e54_emiss_dia = null;
   var $e54_emiss_mes = null;
   var $e54_emiss_ano = null;
   var $e54_emiss = null;
   var $e54_resumo = null;
   var $e54_codtipo = 0;
   var $e54_instit = 0;
   var $e54_depto = 0;
   var $e54_concarpeculiar = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e54_autori = int4 = Autorização
                 e54_numcgm = int4 = Numcgm
                 e54_login = int4 = Login
                 e54_codcom = int4 = Tipo de compra
                 e54_destin = varchar(40) = Destino
                 e54_valor = float8 = Valor
                 e54_anousu = int4 = Ano
                 e54_tipol = char(1) = Tipo de licitação
                 e54_numerl = varchar(8) = Numero da licitação
                 e54_praent = varchar(30) = Prazo de entrega
                 e54_entpar = varchar(30) = Observações
                 e54_conpag = varchar(30) = Condição de pagamento
                 e54_codout = varchar(30) = Outras condições
                 e54_contat = varchar(20) = Contato
                 e54_telef = varchar(20) = Telefone
                 e54_numsol = int4 = Numero da solicitação
                 e54_anulad = date = Data da anulação
                 e54_emiss = date = Data emissão
                 e54_resumo = text = Resumo
                 e54_codtipo = int4 = Tipo Empenho
                 e54_instit = int4 = codigo da instituicao
                 e54_depto = int4 = Depart.
                 e54_concarpeculiar = varchar(100) = Caracteristica Peculiar
                 ";
   //funcao construtor da classe
   function cl_empautoriza() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empautoriza");
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
       $this->e54_autori = ($this->e54_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_autori"]:$this->e54_autori);
       $this->e54_numcgm = ($this->e54_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_numcgm"]:$this->e54_numcgm);
       $this->e54_login = ($this->e54_login == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_login"]:$this->e54_login);
       $this->e54_codcom = ($this->e54_codcom == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_codcom"]:$this->e54_codcom);
       $this->e54_destin = ($this->e54_destin == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_destin"]:$this->e54_destin);
       $this->e54_valor = ($this->e54_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_valor"]:$this->e54_valor);
       $this->e54_anousu = ($this->e54_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_anousu"]:$this->e54_anousu);
       $this->e54_tipol = ($this->e54_tipol == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_tipol"]:$this->e54_tipol);
       $this->e54_numerl = ($this->e54_numerl == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_numerl"]:$this->e54_numerl);
       $this->e54_praent = ($this->e54_praent == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_praent"]:$this->e54_praent);
       $this->e54_entpar = ($this->e54_entpar == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_entpar"]:$this->e54_entpar);
       $this->e54_conpag = ($this->e54_conpag == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_conpag"]:$this->e54_conpag);
       $this->e54_codout = ($this->e54_codout == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_codout"]:$this->e54_codout);
       $this->e54_contat = ($this->e54_contat == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_contat"]:$this->e54_contat);
       $this->e54_telef = ($this->e54_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_telef"]:$this->e54_telef);
       $this->e54_numsol = ($this->e54_numsol == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_numsol"]:$this->e54_numsol);
       if($this->e54_anulad == ""){
         $this->e54_anulad_dia = ($this->e54_anulad_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_anulad_dia"]:$this->e54_anulad_dia);
         $this->e54_anulad_mes = ($this->e54_anulad_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_anulad_mes"]:$this->e54_anulad_mes);
         $this->e54_anulad_ano = ($this->e54_anulad_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_anulad_ano"]:$this->e54_anulad_ano);
         if($this->e54_anulad_dia != ""){
            $this->e54_anulad = $this->e54_anulad_ano."-".$this->e54_anulad_mes."-".$this->e54_anulad_dia;
         }
       }
       if($this->e54_emiss == ""){
         $this->e54_emiss_dia = ($this->e54_emiss_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_emiss_dia"]:$this->e54_emiss_dia);
         $this->e54_emiss_mes = ($this->e54_emiss_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_emiss_mes"]:$this->e54_emiss_mes);
         $this->e54_emiss_ano = ($this->e54_emiss_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_emiss_ano"]:$this->e54_emiss_ano);
         if($this->e54_emiss_dia != ""){
            $this->e54_emiss = $this->e54_emiss_ano."-".$this->e54_emiss_mes."-".$this->e54_emiss_dia;
         }
       }
       $this->e54_resumo = ($this->e54_resumo == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_resumo"]:$this->e54_resumo);
       $this->e54_codtipo = ($this->e54_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_codtipo"]:$this->e54_codtipo);
       $this->e54_instit = ($this->e54_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_instit"]:$this->e54_instit);
       $this->e54_depto = ($this->e54_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_depto"]:$this->e54_depto);
       $this->e54_concarpeculiar = ($this->e54_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_concarpeculiar"]:$this->e54_concarpeculiar);
     }else{
       $this->e54_autori = ($this->e54_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e54_autori"]:$this->e54_autori);
     }
   }
   // funcao para inclusao
   function incluir ($e54_autori){
      $this->atualizacampos();
     if($this->e54_numcgm == null ){
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "e54_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e54_login == null ){
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "e54_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e54_codcom == null ){
       $this->erro_sql = " Campo Tipo de compra nao Informado.";
       $this->erro_campo = "e54_codcom";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e54_valor == null ){
       $this->e54_valor = "0";
     }
     if($this->e54_anousu == null ){
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "e54_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e54_numsol == null ){
       $this->e54_numsol = "0";
     }
     if($this->e54_anulad == null ){
       $this->e54_anulad = "null";
     }
     if($this->e54_emiss == null ){
       $this->e54_emiss = "null";
     }
     if($this->e54_codtipo == null ){
       $this->erro_sql = " Campo Tipo Empenho nao Informado.";
       $this->erro_campo = "e54_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e54_instit == null ){
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "e54_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e54_depto == null ){
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "e54_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e54_concarpeculiar == null ){
       $this->erro_sql = " Campo Caracteristica Peculiar não informado.";
       $this->erro_campo = "e54_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e54_autori == "" || $e54_autori == null ){
       $result = db_query("select nextval('empautoriza_e54_autori_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empautoriza_e54_autori_seq do campo: e54_autori";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e54_autori = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empautoriza_e54_autori_seq");
       if(($result != false) && (pg_result($result,0,0) < $e54_autori)){
         $this->erro_sql = " Campo e54_autori maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e54_autori = $e54_autori;
       }
     }
     if(($this->e54_autori == null) || ($this->e54_autori == "") ){
       $this->erro_sql = " Campo e54_autori nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empautoriza(
                                       e54_autori
                                      ,e54_numcgm
                                      ,e54_login
                                      ,e54_codcom
                                      ,e54_destin
                                      ,e54_valor
                                      ,e54_anousu
                                      ,e54_tipol
                                      ,e54_numerl
                                      ,e54_praent
                                      ,e54_entpar
                                      ,e54_conpag
                                      ,e54_codout
                                      ,e54_contat
                                      ,e54_telef
                                      ,e54_numsol
                                      ,e54_anulad
                                      ,e54_emiss
                                      ,e54_resumo
                                      ,e54_codtipo
                                      ,e54_instit
                                      ,e54_depto
                                      ,e54_concarpeculiar
                       )
                values (
                                $this->e54_autori
                               ,$this->e54_numcgm
                               ,$this->e54_login
                               ,$this->e54_codcom
                               ,'$this->e54_destin'
                               ,$this->e54_valor
                               ,$this->e54_anousu
                               ,'$this->e54_tipol'
                               ,'$this->e54_numerl'
                               ,'$this->e54_praent'
                               ,'$this->e54_entpar'
                               ,'$this->e54_conpag'
                               ,'$this->e54_codout'
                               ,'$this->e54_contat'
                               ,'$this->e54_telef'
                               ,$this->e54_numsol
                               ,".($this->e54_anulad == "null" || $this->e54_anulad == ""?"null":"'".$this->e54_anulad."'")."
                               ,".($this->e54_emiss == "null" || $this->e54_emiss == ""?"null":"'".$this->e54_emiss."'")."
                               ,'$this->e54_resumo'
                               ,$this->e54_codtipo
                               ,$this->e54_instit
                               ,$this->e54_depto
                               ,'$this->e54_concarpeculiar'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autoriza empenho ($this->e54_autori) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autoriza empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autoriza empenho ($this->e54_autori) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e54_autori;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e54_autori));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5447,'$this->e54_autori','I')");
       $resac = db_query("insert into db_acount values($acount,810,5447,'','".AddSlashes(pg_result($resaco,0,'e54_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5451,'','".AddSlashes(pg_result($resaco,0,'e54_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5463,'','".AddSlashes(pg_result($resaco,0,'e54_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5466,'','".AddSlashes(pg_result($resaco,0,'e54_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5448,'','".AddSlashes(pg_result($resaco,0,'e54_destin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5449,'','".AddSlashes(pg_result($resaco,0,'e54_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5450,'','".AddSlashes(pg_result($resaco,0,'e54_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5452,'','".AddSlashes(pg_result($resaco,0,'e54_tipol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5453,'','".AddSlashes(pg_result($resaco,0,'e54_numerl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5454,'','".AddSlashes(pg_result($resaco,0,'e54_praent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5455,'','".AddSlashes(pg_result($resaco,0,'e54_entpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5456,'','".AddSlashes(pg_result($resaco,0,'e54_conpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5457,'','".AddSlashes(pg_result($resaco,0,'e54_codout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5458,'','".AddSlashes(pg_result($resaco,0,'e54_contat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5459,'','".AddSlashes(pg_result($resaco,0,'e54_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5460,'','".AddSlashes(pg_result($resaco,0,'e54_numsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5461,'','".AddSlashes(pg_result($resaco,0,'e54_anulad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5462,'','".AddSlashes(pg_result($resaco,0,'e54_emiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5464,'','".AddSlashes(pg_result($resaco,0,'e54_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5592,'','".AddSlashes(pg_result($resaco,0,'e54_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,5593,'','".AddSlashes(pg_result($resaco,0,'e54_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,6612,'','".AddSlashes(pg_result($resaco,0,'e54_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,810,10816,'','".AddSlashes(pg_result($resaco,0,'e54_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($e54_autori=null) {
      $this->atualizacampos();
     $sql = " update empautoriza set ";
     $virgula = "";
     if(trim($this->e54_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_autori"])){
       $sql  .= $virgula." e54_autori = $this->e54_autori ";
       $virgula = ",";
       if(trim($this->e54_autori) == null ){
         $this->erro_sql = " Campo Autorização nao Informado.";
         $this->erro_campo = "e54_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e54_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_numcgm"])){
       $sql  .= $virgula." e54_numcgm = $this->e54_numcgm ";
       $virgula = ",";
       if(trim($this->e54_numcgm) == null ){
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "e54_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e54_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_login"])){
       $sql  .= $virgula." e54_login = $this->e54_login ";
       $virgula = ",";
       if(trim($this->e54_login) == null ){
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "e54_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e54_codcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_codcom"])){
       $sql  .= $virgula." e54_codcom = $this->e54_codcom ";
       $virgula = ",";
       if(trim($this->e54_codcom) == null ){
         $this->erro_sql = " Campo Tipo de compra nao Informado.";
         $this->erro_campo = "e54_codcom";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e54_destin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_destin"])){
       $sql  .= $virgula." e54_destin = '$this->e54_destin' ";
       $virgula = ",";
     }
     if(trim($this->e54_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_valor"])){
        if(trim($this->e54_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e54_valor"])){
           $this->e54_valor = "0" ;
        }
       $sql  .= $virgula." e54_valor = $this->e54_valor ";
       $virgula = ",";
     }
     if(trim($this->e54_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_anousu"])){
       $sql  .= $virgula." e54_anousu = $this->e54_anousu ";
       $virgula = ",";
       if(trim($this->e54_anousu) == null ){
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "e54_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e54_tipol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_tipol"])){
       $sql  .= $virgula." e54_tipol = '$this->e54_tipol' ";
       $virgula = ",";
     }
     if(trim($this->e54_numerl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_numerl"])){
       $sql  .= $virgula." e54_numerl = '$this->e54_numerl' ";
       $virgula = ",";
     }
     if(trim($this->e54_praent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_praent"])){
       $sql  .= $virgula." e54_praent = '$this->e54_praent' ";
       $virgula = ",";
     }
     if(trim($this->e54_entpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_entpar"])){
       $sql  .= $virgula." e54_entpar = '$this->e54_entpar' ";
       $virgula = ",";
     }
     if(trim($this->e54_conpag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_conpag"])){
       $sql  .= $virgula." e54_conpag = '$this->e54_conpag' ";
       $virgula = ",";
     }
     if(trim($this->e54_codout)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_codout"])){
       $sql  .= $virgula." e54_codout = '$this->e54_codout' ";
       $virgula = ",";
     }
     if(trim($this->e54_contat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_contat"])){
       $sql  .= $virgula." e54_contat = '$this->e54_contat' ";
       $virgula = ",";
     }
     if(trim($this->e54_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_telef"])){
       $sql  .= $virgula." e54_telef = '$this->e54_telef' ";
       $virgula = ",";
     }
     if(trim($this->e54_numsol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_numsol"])){
        if(trim($this->e54_numsol)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e54_numsol"])){
           $this->e54_numsol = "0" ;
        }
       $sql  .= $virgula." e54_numsol = $this->e54_numsol ";
       $virgula = ",";
     }
     if(trim($this->e54_anulad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_anulad_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e54_anulad_dia"] !="") ){
       $sql  .= $virgula." e54_anulad = '$this->e54_anulad' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e54_anulad_dia"])){
         $sql  .= $virgula." e54_anulad = null ";
         $virgula = ",";
       }
     }
     if(trim($this->e54_emiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_emiss_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e54_emiss_dia"] !="") ){
       $sql  .= $virgula." e54_emiss = '$this->e54_emiss' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e54_emiss_dia"])){
         $sql  .= $virgula." e54_emiss = null ";
         $virgula = ",";
       }
     }
     if(trim($this->e54_resumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_resumo"])){
       $sql  .= $virgula." e54_resumo = '$this->e54_resumo' ";
       $virgula = ",";
     }
     if(trim($this->e54_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_codtipo"])){
       $sql  .= $virgula." e54_codtipo = $this->e54_codtipo ";
       $virgula = ",";
       if(trim($this->e54_codtipo) == null ){
         $this->erro_sql = " Campo Tipo Empenho nao Informado.";
         $this->erro_campo = "e54_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e54_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_instit"])){
       $sql  .= $virgula." e54_instit = $this->e54_instit ";
       $virgula = ",";
       if(trim($this->e54_instit) == null ){
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "e54_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e54_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_depto"])){
       $sql  .= $virgula." e54_depto = $this->e54_depto ";
       $virgula = ",";
       if(trim($this->e54_depto) == null ){
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "e54_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e54_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e54_concarpeculiar"])){
       $sql  .= $virgula." e54_concarpeculiar = '$this->e54_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->e54_concarpeculiar) == null ){
         $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
         $this->erro_campo = "e54_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e54_autori!=null){
       $sql .= " e54_autori = $this->e54_autori";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e54_autori));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5447,'$this->e54_autori','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_autori"]) || $this->e54_autori != "")
           $resac = db_query("insert into db_acount values($acount,810,5447,'".AddSlashes(pg_result($resaco,$conresaco,'e54_autori'))."','$this->e54_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_numcgm"]) || $this->e54_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,810,5451,'".AddSlashes(pg_result($resaco,$conresaco,'e54_numcgm'))."','$this->e54_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_login"]) || $this->e54_login != "")
           $resac = db_query("insert into db_acount values($acount,810,5463,'".AddSlashes(pg_result($resaco,$conresaco,'e54_login'))."','$this->e54_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_codcom"]) || $this->e54_codcom != "")
           $resac = db_query("insert into db_acount values($acount,810,5466,'".AddSlashes(pg_result($resaco,$conresaco,'e54_codcom'))."','$this->e54_codcom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_destin"]) || $this->e54_destin != "")
           $resac = db_query("insert into db_acount values($acount,810,5448,'".AddSlashes(pg_result($resaco,$conresaco,'e54_destin'))."','$this->e54_destin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_valor"]) || $this->e54_valor != "")
           $resac = db_query("insert into db_acount values($acount,810,5449,'".AddSlashes(pg_result($resaco,$conresaco,'e54_valor'))."','$this->e54_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_anousu"]) || $this->e54_anousu != "")
           $resac = db_query("insert into db_acount values($acount,810,5450,'".AddSlashes(pg_result($resaco,$conresaco,'e54_anousu'))."','$this->e54_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_tipol"]) || $this->e54_tipol != "")
           $resac = db_query("insert into db_acount values($acount,810,5452,'".AddSlashes(pg_result($resaco,$conresaco,'e54_tipol'))."','$this->e54_tipol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_numerl"]) || $this->e54_numerl != "")
           $resac = db_query("insert into db_acount values($acount,810,5453,'".AddSlashes(pg_result($resaco,$conresaco,'e54_numerl'))."','$this->e54_numerl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_praent"]) || $this->e54_praent != "")
           $resac = db_query("insert into db_acount values($acount,810,5454,'".AddSlashes(pg_result($resaco,$conresaco,'e54_praent'))."','$this->e54_praent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_entpar"]) || $this->e54_entpar != "")
           $resac = db_query("insert into db_acount values($acount,810,5455,'".AddSlashes(pg_result($resaco,$conresaco,'e54_entpar'))."','$this->e54_entpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_conpag"]) || $this->e54_conpag != "")
           $resac = db_query("insert into db_acount values($acount,810,5456,'".AddSlashes(pg_result($resaco,$conresaco,'e54_conpag'))."','$this->e54_conpag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_codout"]) || $this->e54_codout != "")
           $resac = db_query("insert into db_acount values($acount,810,5457,'".AddSlashes(pg_result($resaco,$conresaco,'e54_codout'))."','$this->e54_codout',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_contat"]) || $this->e54_contat != "")
           $resac = db_query("insert into db_acount values($acount,810,5458,'".AddSlashes(pg_result($resaco,$conresaco,'e54_contat'))."','$this->e54_contat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_telef"]) || $this->e54_telef != "")
           $resac = db_query("insert into db_acount values($acount,810,5459,'".AddSlashes(pg_result($resaco,$conresaco,'e54_telef'))."','$this->e54_telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_numsol"]) || $this->e54_numsol != "")
           $resac = db_query("insert into db_acount values($acount,810,5460,'".AddSlashes(pg_result($resaco,$conresaco,'e54_numsol'))."','$this->e54_numsol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_anulad"]) || $this->e54_anulad != "")
           $resac = db_query("insert into db_acount values($acount,810,5461,'".AddSlashes(pg_result($resaco,$conresaco,'e54_anulad'))."','$this->e54_anulad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_emiss"]) || $this->e54_emiss != "")
           $resac = db_query("insert into db_acount values($acount,810,5462,'".AddSlashes(pg_result($resaco,$conresaco,'e54_emiss'))."','$this->e54_emiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_resumo"]) || $this->e54_resumo != "")
           $resac = db_query("insert into db_acount values($acount,810,5464,'".AddSlashes(pg_result($resaco,$conresaco,'e54_resumo'))."','$this->e54_resumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_codtipo"]) || $this->e54_codtipo != "")
           $resac = db_query("insert into db_acount values($acount,810,5592,'".AddSlashes(pg_result($resaco,$conresaco,'e54_codtipo'))."','$this->e54_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_instit"]) || $this->e54_instit != "")
           $resac = db_query("insert into db_acount values($acount,810,5593,'".AddSlashes(pg_result($resaco,$conresaco,'e54_instit'))."','$this->e54_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_depto"]) || $this->e54_depto != "")
           $resac = db_query("insert into db_acount values($acount,810,6612,'".AddSlashes(pg_result($resaco,$conresaco,'e54_depto'))."','$this->e54_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e54_concarpeculiar"]) || $this->e54_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,810,10816,'".AddSlashes(pg_result($resaco,$conresaco,'e54_concarpeculiar'))."','$this->e54_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autoriza empenho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e54_autori;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autoriza empenho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e54_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e54_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($e54_autori=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e54_autori));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5447,'$e54_autori','E')");
         $resac = db_query("insert into db_acount values($acount,810,5447,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5451,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5463,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5466,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5448,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_destin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5449,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5450,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5452,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_tipol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5453,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_numerl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5454,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_praent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5455,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_entpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5456,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_conpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5457,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_codout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5458,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_contat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5459,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5460,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_numsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5461,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_anulad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5462,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_emiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5464,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5592,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,5593,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,6612,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,810,10816,'','".AddSlashes(pg_result($resaco,$iresaco,'e54_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empautoriza
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e54_autori != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e54_autori = $e54_autori ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autoriza empenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e54_autori;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autoriza empenho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e54_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e54_autori;
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
        $this->erro_sql   = "Record Vazio na Tabela:empautoriza";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e54_autori=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautoriza ";
     $sql .= "      inner join cgm            on cgm.z01_numcgm = empautoriza.e54_numcgm";
     $sql .= "      inner join db_config      on db_config.codigo = empautoriza.e54_instit";
     $sql .= "      inner join db_usuarios    on db_usuarios.id_usuario = empautoriza.e54_login";
     $sql .= "      inner join db_depart      on db_depart.coddepto = empautoriza.e54_depto";
     $sql .= "      inner join pctipocompra   on pctipocompra.pc50_codcom = empautoriza.e54_codcom";
     $sql .= "      inner join concarpeculiar on concarpeculiar.c58_sequencial = empautoriza.e54_concarpeculiar";
     $sql .= "      left  join empempaut      on empautoriza.e54_autori = empempaut.e61_autori";
     $sql .= "      left  join empempenho     on empempenho.e60_numemp = empempaut.e61_numemp";
     $sql .= "      left  join empautidot     on e56_autori = empautoriza.e54_autori and e56_anousu=e54_anousu ";
     $sql .= "      left join orcdotacao      on e56_Coddot = o58_coddot and e56_anousu = o58_anousu";

     $sql2 = "";
     if($dbwhere==""){
       if($e54_autori!=null ){
         $sql2 .= " where empautoriza.e54_autori = $e54_autori ";
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

  function sql_query_processo($iAutorizacao = null, $sCampos = "*", $sOrdem = null, $sWhere = ""){

    $sql  = " select {$sCampos} ";
    $sql .= "  from empautoriza ";
    $sql .= "       left join empautorizaprocesso on empautorizaprocesso.e150_empautoriza = empautoriza.e54_autori ";

    if (!empty($iAutorizacao)) {

      $sAutorizacao = "empautoriza.e54_autori = {$iAutorizacao}";
      $sWhere       = (!empty($sWhere) ? " and " : '') . $sAutorizacao;
    }

    if (!empty($sWhere)) {
      $sql .= " where {$sWhere} ";
    }

    if (!empty($sOrdem)) {
      $sql .= " order by {$sOrdem} ";
    }

    return $sql;
  }

   function sql_query_depto ( $e54_autori=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautoriza ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm      ";
     $sql .= "      inner join db_config  on  db_config.codigo = empautoriza.e54_instit   ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login      ";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom  ";
     $sql .= "      left outer join emptipo  on  e41_codtipo = e54_codtipo               ";
     $sql .= "      left join empautidot on e56_autori = empautoriza.e54_autori and e56_anousu=e54_anousu   " ;
     $sql .= "      left join orcdotacao on o58_coddot = empautidot.e56_coddot and o58_anousu = empautidot.e56_anousu   ";
     $sql .= "      left outer join db_departorg on db01_orgao   = orcdotacao.o58_orgao and ";
     $sql .= "                                      db01_unidade = orcdotacao.o58_unidade and ";
     $sql .= "                                      db01_anousu  = orcdotacao.o58_anousu ";
     $sql .= "      left outer join db_depart on coddepto = db_departorg.db01_coddepto   ";

     $sql2 = "";
     if($dbwhere==""){
       if($e54_autori!=null ){
         $sql2 .= " where empautoriza.e54_autori = $e54_autori and  e54_instit=".db_getsession("DB_instit");
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere and e54_instit=".db_getsession("DB_instit");
     }else{
        $sql2 = " where e54_instit=".db_getsession("DB_instit");
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
   function sql_query_deptoautori ( $e54_autori=null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from empautoriza ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm      ";
     $sql .= "      inner join db_config  on  db_config.codigo = empautoriza.e54_instit   ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login      ";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom  ";
     $sql .= "      left join empautorizaprocesso on empautorizaprocesso.e150_empautoriza = empautoriza.e54_autori  ";
     $sql .= "      left outer join emptipo  on  e41_codtipo = e54_codtipo               ";
     $sql .= "      left join empautidot on e56_autori = empautoriza.e54_autori and e56_anousu=e54_anousu   ";
     $sql .= "      left join orcdotacao on o58_coddot = empautidot.e56_coddot and o58_anousu = empautidot.e56_anousu   ";
     $sql .= "      left outer join db_departorg on db01_orgao = orcdotacao.o58_orgao and db01_unidade=orcdotacao.o58_unidade and db01_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join db_depart on coddepto = empautoriza.e54_depto ";
     $sql .= "      left join empempaut on e61_autori = empautoriza.e54_autori ";
     $sql .= "      left join empempenho on e60_numemp = empempaut.e61_numemp ";
     $sql .= "      left  join orcreservaaut        on  orcreservaaut.o83_autori = empautoriza.e54_autori ";
     $sql .= "      left  join orcreserva           on  orcreserva.o80_codres = orcreservaaut.o83_codres ";
     $sql2 = "";
     if($dbwhere==""){
       if($e54_autori!=null ){
         $sql2 .= " where empautoriza.e54_autori = $e54_autori and  e54_instit=".db_getsession("DB_instit");
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }else{
        $sql2 = " where e54_instit=".db_getsession("DB_instit");
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
   function sql_query_elementomaterial ( $e54_autori=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautoriza ";
     $sql .= "      inner join empautitem           on  empautitem.e55_autori = empautoriza.e54_autori ";
     $sql .= "      inner join empautidot           on  empautidot.e56_autori = empautoriza.e54_autori ";
     $sql .= "      inner join orcdotacao           on orcdotacao.o58_coddot = empautidot.e56_coddot ";
     $sql .= "                                     and orcdotacao.o58_anousu = empautoriza.e54_anousu ";
     $sql .= "      inner join orcelemento element  on  element.o56_codele    = orcdotacao.o58_codele ";
     $sql .= "      inner join orcelemento desdobr  on  desdobr.o56_codele    = empautitem.e55_codele ";
     $sql .= "      inner join pcmater              on  empautitem.e55_item   = pcmater.pc01_codmater ";
     $sql .= "      left  join empempaut            on  empempaut.e61_autori  = empautoriza.e54_autori ";
     $sql .= "      left  join empempenho           on  empempenho.e60_numemp = empempaut.e61_numemp ";
     $sql .= "      left  join empelemento          on  empelemento.e64_numemp = empempenho.e60_numemp ";
     $sql .= "      left  join orcreservaaut        on  orcreservaaut.o83_autori = empautoriza.e54_autori ";
     $sql .= "      left  join orcreserva           on  orcreserva.o80_codres = orcreservaaut.o83_codres ";
     $sql .= "                                     and  orcreserva.o80_anousu = empautoriza.e54_anousu ";
     $sql .= "                                     and  orcreserva.o80_coddot = empautidot.e56_coddot ";

     $sql2 = "";
     if($dbwhere==""){
       if($e54_autori!=null ){
         $sql2 .= " where empautoriza.e54_autori = $e54_autori and  e54_instit=".db_getsession("DB_instit");
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }else{
        $sql2 = " where e54_instit=".db_getsession("DB_instit");
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
   function sql_query_file ( $e54_autori=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautoriza ";
     $sql2 = "";
     if($dbwhere==""){
       if($e54_autori!=null ){
         $sql2 .= " where empautoriza.e54_autori = $e54_autori ";
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
   function sql_query_itemmaterial ( $e54_autori=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautoriza ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm";
     /**
     * adiconado o inner abaixo, usando no modulo empenho->consulta de autorização->pesquisa por material
     */
     $sql .= "      inner join empautitem on  e55_autori=e54_autori ";

     $sql2 = "";
     if($dbwhere==""){
       if($e54_autori!=null ){
         $sql2 .= " where empautoriza.e54_autori = $e54_autori ";
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
   function sql_query_solicita ( $e54_autori=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautoriza ";
     $sql .= "      inner join empautitem           on empautitem.e55_autori           = empautoriza.e54_autori" ;
     $sql .= "      inner join empautitempcprocitem on empautitempcprocitem.e73_autori = empautitem.e55_autori ";
     $sql .= "                                     and empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
     $sql .= "      inner join solicitem            on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita             on solicita.pc10_numero            = solicitem.pc11_numero";
     $sql .= "      inner join pcproc               on pcproc.pc80_codproc             = pcprocitem.pc81_codproc";
     $sql2 = "";
     if($dbwhere==""){
       if($e54_autori!=null ){
         $sql2 .= " where empautoriza.e54_autori = $e54_autori and  e54_instit=".db_getsession("DB_instit");
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere and e54_instit=".db_getsession("DB_instit");
     }else{
        $sql2 = " where e54_instit=".db_getsession("DB_instit");
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
   function sql_anulaautorizacao($param_autori = null, $verifica_saldo = false, &$erro_msg = "", &$sqlerro = false, &$flag_saldo = false, $vetor_dotacao = array(), $reservar = "false") {

	    $clempautitem    = new cl_empautitem();
	    $clpcprocitem    = new cl_pcprocitem();
	    $clorcreservaaut = new cl_orcreservaaut();
	    $clorcreserva    = new cl_orcreserva();
	    $clorcreservasol = new cl_orcreservasol();

	    if ($param_autori != null) {

	      $sql_elemt     = $clempautitem->sql_query_autoridot($param_autori, null, "distinct e73_pcprocitem as pc81_codprocitem, e56_coddot, e56_orctiporec");
	      $result_elemt  = @db_query($sql_elemt);
	      $numrows_elemt = @pg_numrows($result_elemt);

	      for ($x = 0; $x < $numrows_elemt; $x++) {

          $lIncluiuReserva  = false;
          $pc81_codprocitem = pg_result($result_elemt, $x, 0);
          $e56_coddot       = pg_result($result_elemt, $x, 1);
          $e56_orctiporec   = pg_result($result_elemt, $x, 2);
          $sWhereDotac      = " pc13_coddot = {$e56_coddot} ";
	        if ($pc81_codprocitem != "") {
            $sWhereDotac .= " and pc81_codprocitem = {$pc81_codprocitem} ";
	        }
	        if ($e56_orctiporec != "") {
	          $sWhereDotac .= " and pc19_orctiporec = {$e56_orctiporec} ";
	        } else {
	          $sWhereDotac .= " and pc19_orctiporec is null ";
	        }

	        if (empty($pc81_codprocitem)) {
	        	continue;
	        }

	        $sql_item = $clpcprocitem->sql_query_dotac(null,
	                                                   "distinct pc81_solicitem, pc11_numero, pc11_quant, pc13_valor, pc13_sequencial",
	                                                    null,
	                                                    $sWhereDotac
	                                                   );

          $result_item      = @db_query($sql_item);
          $numrows_procitem = @pg_numrows($result_item);

	        for($xx = 0; $xx < $numrows_procitem; $xx++) {

	          $total_a_reservar    = 0;
	          $nValorOrcReservaSol = 0;
	          $pc81_solicitem      = pg_result($result_item, $xx, 0);
	          $pc11_numero         = pg_result($result_item, $xx, 1);
	          $pc11_quant          = pg_result($result_item, $xx, 2);
	          $pc13_valor          = pg_result($result_item, $xx, 3);
	          $pc13_sequencial     = pg_result($result_item, $xx, 4);
	          $sql_dotac = $clorcreservaaut->sql_query_orcreserva(
	                                                             null,
	                                                             "o80_coddot,
	                                                              o80_anousu,
	                                                              o80_dtfim,
	                                                              o80_dtini,
	                                                              o80_dtlanc,
	                                                              o80_descr,
	                                                              o80_codres,
	                                                              o80_valor", "",
	                                                              " o80_coddot=$e56_coddot
	                                                              and o83_autori=" . $param_autori);

	          $result_dotac  = @db_query($sql_dotac);
	          $numrows_dotac = @pg_numrows($result_dotac);

	          if ($numrows_dotac > 0) {

	            $o80_coddot    = pg_result($result_dotac, 0, 0);
	            $o80_anousu    = pg_result($result_dotac, 0, 1);
	            $o80_dtfim     = pg_result($result_dotac, 0, 2);
	            $o80_dtini     = pg_result($result_dotac, 0, 3);
	            $o80_dtlanc    = pg_result($result_dotac, 0, 4);
	            $o80_descr     = pg_result($result_dotac, 0, 5);
	            $o80_codres    = pg_result($result_dotac, 0, 6);
	            $valor_reserva = pg_result($result_dotac, 0, 7);
	          } else {

	            $o80_anousu = db_getsession("DB_anousu");
	            $o80_coddot = $e56_coddot;
	            $o80_dtfim  = db_getsession("DB_anousu") . "-12-31";
	            $o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
	            $o80_dtlanc = $o80_dtini;
	            $valor_reserva = 0;
	          }

	          if ($verifica_saldo == true) {

	            if ($numrows_dotac > 0) {

                $res_dotacao = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=" . $o80_coddot, db_getsession("DB_anousu"));
	              $NumFields   = pg_numfields($res_dotacao);
                for ($col = 0; $col < $NumFields; $col ++) {

                  $coluna = pg_fieldname($res_dotacao, $col);
	                if ($coluna == "atual_menos_reservado") {

                    $$coluna = pg_result($res_dotacao, 0, $col);
	                  break;
	                }
	              }

	              $saldo = (0 + $atual_menos_reservado);
	              $saldo = trim(str_replace(".", "", db_formatar($saldo, "f")));
	              $vetor_dotacao[$o80_coddot] = str_replace(",", ".", $saldo);
	            }
	          }
	          if ($verifica_saldo == false) {

		          $sWhere              = " o82_pcdotac = {$pc13_sequencial} ";
		          $sSqlOrcReservaSol   = $clorcreservasol->sql_query(null, "orcreservasol.*, orcreserva.o80_valor", null, $sWhere);
		          $rsSqlOrcReservaSol  = $clorcreservasol->sql_record($sSqlOrcReservaSol);

		          if ($clorcreservasol->numrows > 0) {

		            $oOrcReservaSol      = db_utils::fieldsMemory($rsSqlOrcReservaSol, 0);
		            $nValorOrcReservaSol = $oOrcReservaSol->o80_valor;
		            if ($sqlerro == false) {

		              $clorcreservasol->excluir($oOrcReservaSol->o82_sequencial);
		              if ($clorcreservasol->erro_status == 0) {

		                $erro_msg = $clorcreservasol->erro_msg;
		                $sqlerro  = true;
		                break;
		              }
		            }

		            if ($sqlerro == false) {

		              $clorcreserva->excluir($oOrcReservaSol->o82_codres);
		              if ($clorcreserva->erro_status == 0) {

		                $erro_msg = $clorcreserva->erro_msg;
		                $sqlerro  = true;
		                break;
		              }
		            }
		          }

	            // Anulacao de Autorizacao
	            // mesmo que reservar seja NAO(false) tem que excluir as reservas existentes
	            if ($valor_reserva > 0 && $reservar == false) {

	              if (isset($o80_codres) && trim($o80_codres) != "") {

                  $clorcreservaaut->excluir($o80_codres);
	                if ($clorcreservaaut->erro_status == 0) {

                    $erro_msg = $clorcreservaaut->erro_msg;
                    $sqlerro  = true;
	                  break;
	                }
	              }

	              if ($sqlerro == false && isset($o80_codres) && trim($o80_codres) != "") {

                  $clorcreserva->excluir($o80_codres);
	                if ($clorcreserva->erro_status == 0) {

                    $erro_msg = $clorcreserva->erro_msg;
	                  $sqlerro  = true;
	                  break;
	                }
	              }
	            }

	            if ($reservar == true) {

	              if (isset($o80_codres) && trim($o80_codres) != "") {

                  $clorcreservaaut->excluir($o80_codres);
                  if ($clorcreservaaut->erro_status == 0) {

                    $erro_msg = $clorcreservaaut->erro_msg;
                    $sqlerro = true;
	                  break;
	                }
	              }

	              if ($sqlerro == false && isset($o80_codres) && trim($o80_codres) != "") {

                  $clorcreserva->excluir($o80_codres);
	                if ($clorcreserva->erro_status == 0) {

                    $erro_msg = $clorcreserva->erro_msg;
	                  $sqlerro  = true;
	                  break;
	                }
	              }

	              if ($sqlerro == false) {

	               	$o80_valor = ($valor_reserva + $nValorOrcReservaSol);
                  if ($pc13_valor > 0) {

	                  $clorcreserva->o80_anousu = $o80_anousu;
	                  $clorcreserva->o80_coddot = $o80_coddot;
	                  $clorcreserva->o80_dtfim  = $o80_dtfim;
	                  $clorcreserva->o80_dtini  = $o80_dtini;
	                  $clorcreserva->o80_dtlanc = $o80_dtlanc;
	                  $clorcreserva->o80_valor  = $o80_valor;
	                  if (! isset($o80_descr) || trim($o80_descr) == "" || $o80_descr == null) {
	                    $o80_descr = " ";
	                  }

	                  $clorcreserva->o80_descr  = $o80_descr;

	                  // Anulacao de empenho, valor de cada item eh comparado ao saldo da dotacao
	                  if ($valor_reserva == 0) {

                      $total_a_reservar = $o80_valor;
	                    if (count($vetor_dotacao) > 0 && trim($vetor_dotacao [$o80_coddot]) != "") {

	                      // Valor do item eh menor que o saldo disponivel = saldo disp. da dotacao + valor anulado do empenho
	                      if ($total_a_reservar < $vetor_dotacao [$o80_coddot] && $vetor_dotacao [$o80_coddot] > 0) {
	                        $clorcreserva->incluir(null);
                          $lIncluiuReserva = true;
	                        // Caso contrario, se Valor do item eh maior que saldo da dotacao
	                        // entao, reserva todo o saldo para o item
	                      } elseif ($vetor_dotacao [$o80_coddot] > 0) {
	                        $clorcreserva->o80_valor = $vetor_dotacao [$o80_coddot];
                          $clorcreserva->incluir(null);
	                        $lIncluiuReserva = true;
	                      }
	                      $vetor_dotacao[$o80_coddot] -= $total_a_reservar;
	                    }
	                  }

	                  // Anulacao de autorizacao
	                  if (count($vetor_dotacao) > 0 && trim($vetor_dotacao [$o80_coddot]) != "" && $valor_reserva > 0) {

                      $vetor_dotacao [$o80_coddot] += $valor_reserva;
                      if ($o80_valor > $vetor_dotacao [$o80_coddot]) {

                        $valor_anterior = $o80_valor;
	                      $o80_valor = $vetor_dotacao[$o80_coddot];
	                      $vetor_dotacao [$o80_coddot] = 0;
	                      $clorcreserva->o80_valor = $o80_valor;
	                      $erro_msg = "Valor da solicitacao: R$ " . db_formatar($valor_anterior, "f") . "\\n" . "Valor reservado:      R$ " . db_formatar($o80_valor, "f");
	                      $flag_saldo = true;
	                    }
	                  }

	                  // Quando for Anulacao de autorizacao
	                  if ($valor_reserva > 0 && $lIncluiuReserva == false) {
                      $clorcreserva->incluir(null);
	                  }
	                  if ($clorcreserva->erro_status == 0) {

	                    $sqlerro = true;
	                    $erro_msg = "Nao foi possivel recriar reserva de solicitacao " . $pc11_numero;
	                    break;
	                  } else {

	                    $codres = $clorcreserva->o80_codres;
	                    $clorcreservasol->o82_codres    = $codres;
	                    $clorcreservasol->o82_solicitem = $pc81_solicitem;
	                    $clorcreservasol->o82_pcdotac   = $pc13_sequencial;
	                    $clorcreservasol->incluir(null);
	                    if ($clorcreservasol->erro_status == 0) {
	                      $sqlerro = true;
	                      $erro_msg = "Ocorreu erro ao inclusao reserva para solicitacao " . $pc11_numero;
	                      break;
	                    }
	                  }
	                }
	              }
	            } // fim do if reservar == true
	          } // fim do if verificado_saldo == false
	        } // fim do 2 for xx
	      } // fim do 1 for x

	      if ($sqlerro == false) {

          $e54_anulad = date("Y-m-d", db_getsession("DB_datausu"));
          $this->e54_anulad = $e54_anulad;
          $this->e54_autori = $param_autori;
          $this->alterar($param_autori);
	        if ($this->erro_status == 0) {
	          $sqlerro = true;
	        }

          if ($flag_saldo == false) {
            $erro_msg = $this->erro_msg;
	        }
        }
      } // fim do if condicional com a autorizacao
   }  // fim da function

   function anulaAutorizacao ( $iCodAutori, $lReservar = false ) {

     $clempautitem    = new cl_empautitem();
     $clpcprocitem    = new cl_pcprocitem();
     $clorcreservaaut = new cl_orcreservaaut();
     $clorcreserva    = new cl_orcreserva();
     $clorcreservasol = new cl_orcreservasol();

     /**
      *
      * Buscamos os dados da reserva e da reserva de autorização e armazenamos as informações na variável $oDadosOrcReserva
      *
      */
     $sSqlOrcReservaAut   = $clorcreservaaut->sql_query_orcreserva(null, "*", null, "o83_autori = {$iCodAutori}");
     $rsOrcReservaAut     = db_query($sSqlOrcReservaAut);
     $oDadosOrcReserva    = db_utils::fieldsMemory($rsOrcReservaAut, 0);

     /**
      *
      * Excluimos a reserva de autorização (orcreservaaut e orcreserva)
      *
      */
     if (!empty($oDadosOrcReserva->o80_codres)){
	     $clorcreservaaut->excluir($oDadosOrcReserva->o80_codres);
	     if ($clorcreservaaut->erro_status == 0) {
	       throw new Exception("Erro[1] - Erro ao excluir reserva de saldo da autorização.\n{$clorcreservaaut->erro_msg}");
	     }

	     $clorcreserva->excluir($oDadosOrcReserva->o80_codres);
	     if ($clorcreserva->erro_status == 0) {
	       throw new Exception("Erro[2] - Erro ao excluir reserva de saldo da autorização.\n{$clorcreserva->erro_msg}");
	     }
     }
	     /*
	      * Caso $lReservar seja true significa que o usuário que recriar as reservas de saldo dos itens da solicitação
	      * que gerou a autorização.
	      *
	      */
	     if ($lReservar) {

	     	/*
	     	 * Verificamos os itens da autorização para buscarmos o processo de compras e chegar até a solicitação de compras
	     	 * que gerou a autorização.
	     	 */
	       $sSqlItens     = $clempautitem->sql_query_autoridot($iCodAutori,
	                                                           null,
	                                                           "distinct
	                                                            e73_pcprocitem as pc81_codprocitem,
	                                                            e55_vltot,
	                                                            e56_coddot,
	                                                            e56_orctiporec");
	       $rsItens       = db_query($sSqlItens);
	       $iNumRowsItens = pg_numrows($rsItens);

	       for ($x = 0; $x < $iNumRowsItens; $x++) {

	         $oItem = db_utils::fieldsMemory($rsItens, $x);

	         $sWhereDotac      = " pc13_coddot = {$oItem->e56_coddot}";
	         if ($oItem->pc81_codprocitem != "") {
	          $sWhereDotac .= " and pc81_codprocitem = {$oItem->pc81_codprocitem} ";
	         }
	         if ($oItem->e56_orctiporec != "") {
	           $sWhereDotac .= " and pc19_orctiporec = {$oItem->e56_orctiporec} ";
	         } else {
	           $sWhereDotac .= " and pc19_orctiporec is null";
	         }

	         if (empty($oItem->pc81_codprocitem)) {
	           continue;
	         }

	        /**
	         * Verificamos os itens da solicitação para recriarmos a reserva de saldo da solicitação que originou a autorização
	         *
	         */
	        $sSqlItensSolicitacao = $clpcprocitem->sql_query_dotacao_reserva(null,
	                                                                         "distinct
	                                                                          pc81_solicitem,
	                                                                          pc10_data,
	                                                                          pc11_numero,
	                                                                          pc11_quant,
	                                                                          pc13_coddot,
	                                                                          pc13_valor,
	                                                                          extract(year from pc10_data) as pc10_exerc,
	                                                                          pc13_sequencial,
	                                                                          coalesce(o80_valor,0) as o80_valor,
	                                                                          o80_codres",
	                                                                         null,
	                                                                         $sWhereDotac );

	        $rsItensSolicitacao     = db_query($sSqlItensSolicitacao);
	        $iTotalItensSolicitacao = pg_num_rows($rsItensSolicitacao);

	        for($xx = 0; $xx < $iTotalItensSolicitacao; $xx ++) {

	          $oItensSolicitacao = db_utils::fieldsMemory($rsItensSolicitacao, $xx);

	          /*
	           * Se o campo o80_codres não for nulo, significa que a autorização foi gerada parcialmente apartir da solicitação.
	           *
	           * Excluímos a reserva já existente da solicitação para regerarmos com o valor da atual da reserva da solicitação
	           * somado ao valor autorização de empenho.
	           *
	           */

	          if (!empty($oItensSolicitacao->o80_codres)) {

	            /*
	             * Exclui da orcreservasol e orcreserva
	             */
	            $clorcreservasol->excluir(null, "o82_codres={$oItensSolicitacao->o80_codres}");
	            if ($clorcreservasol->erro_status == 0) {
	              throw new Exception("Erro[3] - Erro ao excluir reserva de saldo da solicitação.\n{$clorcreservasol->erro_msg}");
	            }

	            $clorcreserva->excluir($oItensSolicitacao->o80_codres);
	            if ($clorcreserva->erro_status == 0) {
	              throw new Exception("Erro[4] - Erro ao excluir reserva de saldo de solicitação.\n{$clorcreserva->erro_msg}");
	            }

	          }


	          /*
	           * Inclui reserva da solicitação
	           *
	           */
	          $clorcreserva->o80_anousu = $oItensSolicitacao->pc10_exerc;
	          $clorcreserva->o80_coddot = $oItensSolicitacao->pc13_coddot;
	          $clorcreserva->o80_dtfim  = $oItensSolicitacao->pc10_exerc."-12-31";
	          $clorcreserva->o80_dtini  = $oItensSolicitacao->pc10_data;
	          $clorcreserva->o80_dtlanc = $oItensSolicitacao->pc10_data;
	          $clorcreserva->o80_valor  = $oItem->e55_vltot+$oItensSolicitacao->o80_valor;
	          $clorcreserva->o80_descr  = "Reserva automatica solicitação {$oItensSolicitacao->pc11_numero}";
	          $clorcreserva->incluir(null);
	          if ($clorcreserva->erro_status == 0) {
	           throw new Exception("Erro[5] - Erro ao incluir reserva de saldo para a solicitação.\n{$clorcreserva->erro_msg}");
	          }

	          $clorcreservasol->o82_codres     = $clorcreserva->o80_codres;
	          $clorcreservasol->o82_solicitem  = $oItensSolicitacao->pc81_solicitem;
	          $clorcreservasol->o82_sequencial = $oItensSolicitacao->pc13_sequencial;
	          $clorcreservasol->o82_pcdotac    = $oItensSolicitacao->pc13_sequencial;
	          $clorcreservasol->incluir(null);
	          if ($clorcreservasol->erro_status == 0) {
	           throw new Exception("Erro[6] - Erro ao incluir reserva de saldo para a solicitação.\n{$clorcreservasol->erro_msg}");
	          }

	        }

	       }
	     }
      /**
       * Verifica se a solicitação possui fornecedores sugeridos e exclui o processo de compras
       */
      $sSqlSolicitacao = $this->sql_query_solicita( null,
                                                    "distinct pc80_codproc",
                                                    null,
                                                    " e54_autori = {$iCodAutori} " );
      $rsSolicitacao   = db_query($sSqlSolicitacao);

      if ($rsSolicitacao && pg_num_rows($rsSolicitacao) > 0) {

        $iCodigoProcessoCompra = db_utils::fieldsMemory($rsSolicitacao, 0)->pc80_codproc;

        $sSqlDeleta = "delete
                         from empautitempcprocitem
                        where e73_pcprocitem in (select pc81_codprocitem from pcprocitem where pc81_codproc = {$iCodigoProcessoCompra})
                          and e73_autori = {$iCodAutori}";

        $rsDeleta = db_query($sSqlDeleta);

      }

     /*
      * Alteramos a situação da autorização de empenho para anulada
      */
     $e54_anulad = date("Y-m-d", db_getsession("DB_datausu"));
     $this->e54_anulad = $e54_anulad;
     $this->e54_autori = $iCodAutori;
     $this->alterar($iCodAutori);
     if ($this->erro_status == 0) {
       throw new Exception("Erro[7] - {$iCodAutori} Erro ao alterar situação da autorização para anulada.\n{$this->erro_msg}");
     }

   }

	 function sql_query_empenho( $e54_autori=null,$campos="*",$ordem=null,$dbwhere=""){
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
	     $sql .= " from empautoriza ";
	     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm      ";
	     $sql .= "      inner join db_config  on  db_config.codigo = empautoriza.e54_instit   ";
	     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login      ";
       $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom  ";
	     $sql .= "      left join empautorizaprocesso on empautorizaprocesso.e150_empautoriza = empautoriza.e54_autori  ";
	     $sql .= "      left outer join emptipo  on  e41_codtipo = e54_codtipo               ";
	     $sql .= "      left join empautidot on e56_autori = empautoriza.e54_autori and e56_anousu=e54_anousu   ";
	     $sql .= "      inner join db_depart on coddepto = empautoriza.e54_depto ";
	     $sql .= "      left join empempaut on e61_autori = empautoriza.e54_autori ";
	     $sql .= "      left join empempenho on e60_numemp = empempaut.e61_numemp ";
	     $sql2 = "";
	     if($dbwhere==""){
	       if($e54_autori!=null ){
	         $sql2 .= " where empautoriza.e54_autori = $e54_autori and  e54_instit=".db_getsession("DB_instit");
	       }
	     }else if($dbwhere != ""){
	       $sql2 = " where $dbwhere";
	     }else{
	        $sql2 = " where e54_instit=".db_getsession("DB_instit");
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