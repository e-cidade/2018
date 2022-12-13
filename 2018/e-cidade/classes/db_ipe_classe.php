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

//MODULO: pessoal
//CLASSE DA ENTIDADE ipe
class cl_ipe {
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
   var $r36_instit = 0;
   var $r36_anousu = 0;
   var $r36_mesusu = 0;
   var $r36_sequencia = 0;
   var $r36_numcgm = 0;
   var $r36_regist = 0;
   var $r36_dtvinc_dia = null;
   var $r36_dtvinc_mes = null;
   var $r36_dtvinc_ano = null;
   var $r36_dtvinc = null;
   var $r36_matric = 0;
   var $r36_estado = null;
   var $r36_dtalt_dia = null;
   var $r36_dtalt_mes = null;
   var $r36_dtalt_ano = null;
   var $r36_dtalt = null;
   var $r36_contr1 = 0;
   var $r36_valorc = 0;
   var $r36_contrato = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 r36_instit = int4 = Cod. Instituição
                 r36_anousu = int4 = Ano
                 r36_mesusu = int4 = Mes do Exercicio
                 r36_sequencia = int4 = Sequencial
                 r36_numcgm = int4 = Numero CGM
                 r36_regist = int4 = Codigo do Funcionario
                 r36_dtvinc = date = Data do Vinculo com IPE
                 r36_matric = int8 = Matricula do IPE
                 r36_estado = varchar(2) = Estado
                 r36_dtalt = date = Data da alteracao da Situacao
                 r36_contr1 = float8 = Valor
                 r36_valorc = float8 = Valor contribuicao funcionario
                 r36_contrato = int8 = Contrato
                 ";
   //funcao construtor da classe
   function cl_ipe() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ipe");
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
       $this->r36_instit = ($this->r36_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_instit"]:$this->r36_instit);
       $this->r36_anousu = ($this->r36_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_anousu"]:$this->r36_anousu);
       $this->r36_mesusu = ($this->r36_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_mesusu"]:$this->r36_mesusu);
       $this->r36_sequencia = ($this->r36_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_sequencia"]:$this->r36_sequencia);
       $this->r36_numcgm = ($this->r36_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_numcgm"]:$this->r36_numcgm);
       $this->r36_regist = ($this->r36_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_regist"]:$this->r36_regist);
       if($this->r36_dtvinc == ""){
         $this->r36_dtvinc_dia = ($this->r36_dtvinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_dtvinc_dia"]:$this->r36_dtvinc_dia);
         $this->r36_dtvinc_mes = ($this->r36_dtvinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_dtvinc_mes"]:$this->r36_dtvinc_mes);
         $this->r36_dtvinc_ano = ($this->r36_dtvinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_dtvinc_ano"]:$this->r36_dtvinc_ano);
         if($this->r36_dtvinc_dia != ""){
            $this->r36_dtvinc = $this->r36_dtvinc_ano."-".$this->r36_dtvinc_mes."-".$this->r36_dtvinc_dia;
         }
       }
       $this->r36_matric = ($this->r36_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_matric"]:$this->r36_matric);
       $this->r36_estado = ($this->r36_estado == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_estado"]:$this->r36_estado);
       if($this->r36_dtalt == ""){
         $this->r36_dtalt_dia = ($this->r36_dtalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_dtalt_dia"]:$this->r36_dtalt_dia);
         $this->r36_dtalt_mes = ($this->r36_dtalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_dtalt_mes"]:$this->r36_dtalt_mes);
         $this->r36_dtalt_ano = ($this->r36_dtalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_dtalt_ano"]:$this->r36_dtalt_ano);
         if($this->r36_dtalt_dia != ""){
            $this->r36_dtalt = $this->r36_dtalt_ano."-".$this->r36_dtalt_mes."-".$this->r36_dtalt_dia;
         }
       }
       $this->r36_contr1 = ($this->r36_contr1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_contr1"]:$this->r36_contr1);
       $this->r36_valorc = ($this->r36_valorc == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_valorc"]:$this->r36_valorc);
       $this->r36_contrato = ($this->r36_contrato == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_contrato"]:$this->r36_contrato);
     }else{
       $this->r36_instit = ($this->r36_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_instit"]:$this->r36_instit);
       $this->r36_anousu = ($this->r36_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_anousu"]:$this->r36_anousu);
       $this->r36_mesusu = ($this->r36_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_mesusu"]:$this->r36_mesusu);
       $this->r36_sequencia = ($this->r36_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["r36_sequencia"]:$this->r36_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($r36_anousu,$r36_mesusu,$r36_sequencia,$r36_instit){
      $this->atualizacampos();
     if($this->r36_numcgm == null ){
       $this->erro_sql = " Campo Numero CGM nao Informado.";
       $this->erro_campo = "r36_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r36_regist == null ){
       $this->r36_regist = "0";
     }
     if($this->r36_dtvinc == null ){
       $this->erro_sql = " Campo Data do Vinculo com IPE nao Informado.";
       $this->erro_campo = "r36_dtvinc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r36_matric == null ){
       $this->r36_matric = "0";
     }
     if($this->r36_estado == null ){
       $this->erro_sql = " Campo Estado nao Informado.";
       $this->erro_campo = "r36_estado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r36_dtalt == null ){
       $this->r36_dtalt = "null";
     }
     if($this->r36_contr1 == null ){
       $this->r36_contr1 = "0";
     }
     if($this->r36_valorc == null ){
       $this->r36_valorc = "0";
     }
     if($this->r36_contrato == null ){
       $this->r36_contrato = "0";
     }
       $this->r36_anousu = $r36_anousu;
       $this->r36_mesusu = $r36_mesusu;
       $this->r36_sequencia = $r36_sequencia;
       $this->r36_instit = $r36_instit;
     if(($this->r36_anousu == null) || ($this->r36_anousu == "") ){
       $this->erro_sql = " Campo r36_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r36_mesusu == null) || ($this->r36_mesusu == "") ){
       $this->erro_sql = " Campo r36_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r36_sequencia == null) || ($this->r36_sequencia == "") ){
       $this->erro_sql = " Campo r36_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r36_instit == null) || ($this->r36_instit == "") ){
       $this->erro_sql = " Campo r36_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ipe(
                                       r36_instit
                                      ,r36_anousu
                                      ,r36_mesusu
                                      ,r36_sequencia
                                      ,r36_numcgm
                                      ,r36_regist
                                      ,r36_dtvinc
                                      ,r36_matric
                                      ,r36_estado
                                      ,r36_dtalt
                                      ,r36_contr1
                                      ,r36_valorc
                                      ,r36_contrato
                       )
                values (
                                $this->r36_instit
                               ,$this->r36_anousu
                               ,$this->r36_mesusu
                               ,$this->r36_sequencia
                               ,$this->r36_numcgm
                               ,$this->r36_regist
                               ,".($this->r36_dtvinc == "null" || $this->r36_dtvinc == ""?"null":"'".$this->r36_dtvinc."'")."
                               ,$this->r36_matric
                               ,'$this->r36_estado'
                               ,".($this->r36_dtalt == "null" || $this->r36_dtalt == ""?"null":"'".$this->r36_dtalt."'")."
                               ,$this->r36_contr1
                               ,$this->r36_valorc
                               ,$this->r36_contrato
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados para I.P.E. ($this->r36_anousu."-".$this->r36_mesusu."-".$this->r36_sequencia."-".$this->r36_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados para I.P.E. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados para I.P.E. ($this->r36_anousu."-".$this->r36_mesusu."-".$this->r36_sequencia."-".$this->r36_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r36_anousu."-".$this->r36_mesusu."-".$this->r36_sequencia."-".$this->r36_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r36_anousu,$this->r36_mesusu,$this->r36_sequencia,$this->r36_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4021,'$this->r36_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4022,'$this->r36_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,9578,'$this->r36_sequencia','I')");
       $resac = db_query("insert into db_acountkey values($acount,9895,'$this->r36_instit','I')");
       $resac = db_query("insert into db_acount values($acount,562,9895,'','".AddSlashes(pg_result($resaco,0,'r36_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4021,'','".AddSlashes(pg_result($resaco,0,'r36_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4022,'','".AddSlashes(pg_result($resaco,0,'r36_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,9578,'','".AddSlashes(pg_result($resaco,0,'r36_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4028,'','".AddSlashes(pg_result($resaco,0,'r36_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4030,'','".AddSlashes(pg_result($resaco,0,'r36_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4023,'','".AddSlashes(pg_result($resaco,0,'r36_dtvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4024,'','".AddSlashes(pg_result($resaco,0,'r36_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4025,'','".AddSlashes(pg_result($resaco,0,'r36_estado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4026,'','".AddSlashes(pg_result($resaco,0,'r36_dtalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4027,'','".AddSlashes(pg_result($resaco,0,'r36_contr1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,4029,'','".AddSlashes(pg_result($resaco,0,'r36_valorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,562,8870,'','".AddSlashes(pg_result($resaco,0,'r36_contrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($r36_anousu=null,$r36_mesusu=null,$r36_sequencia=null,$r36_instit=null) {
      $this->atualizacampos();
     $sql = " update ipe set ";
     $virgula = "";
     if(trim($this->r36_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_instit"])){
       $sql  .= $virgula." r36_instit = $this->r36_instit ";
       $virgula = ",";
       if(trim($this->r36_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r36_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r36_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_anousu"])){
       $sql  .= $virgula." r36_anousu = $this->r36_anousu ";
       $virgula = ",";
       if(trim($this->r36_anousu) == null ){
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "r36_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r36_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_mesusu"])){
       $sql  .= $virgula." r36_mesusu = $this->r36_mesusu ";
       $virgula = ",";
       if(trim($this->r36_mesusu) == null ){
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r36_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r36_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_sequencia"])){
       $sql  .= $virgula." r36_sequencia = $this->r36_sequencia ";
       $virgula = ",";
       if(trim($this->r36_sequencia) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "r36_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r36_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_numcgm"])){
       $sql  .= $virgula." r36_numcgm = $this->r36_numcgm ";
       $virgula = ",";
       if(trim($this->r36_numcgm) == null ){
         $this->erro_sql = " Campo Numero CGM nao Informado.";
         $this->erro_campo = "r36_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r36_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_regist"])){
        if(trim($this->r36_regist)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r36_regist"])){
           $this->r36_regist = "0" ;
        }
       $sql  .= $virgula." r36_regist = $this->r36_regist ";
       $virgula = ",";
     }
     if(trim($this->r36_dtvinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_dtvinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r36_dtvinc_dia"] !="") ){
       $sql  .= $virgula." r36_dtvinc = '$this->r36_dtvinc' ";
       $virgula = ",";
       if(trim($this->r36_dtvinc) == null ){
         $this->erro_sql = " Campo Data do Vinculo com IPE nao Informado.";
         $this->erro_campo = "r36_dtvinc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["r36_dtvinc_dia"])){
         $sql  .= $virgula." r36_dtvinc = null ";
         $virgula = ",";
         if(trim($this->r36_dtvinc) == null ){
           $this->erro_sql = " Campo Data do Vinculo com IPE nao Informado.";
           $this->erro_campo = "r36_dtvinc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r36_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_matric"])){
        if(trim($this->r36_matric)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r36_matric"])){
           $this->r36_matric = "0" ;
        }
       $sql  .= $virgula." r36_matric = $this->r36_matric ";
       $virgula = ",";
     }
     if(trim($this->r36_estado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_estado"])){
       $sql  .= $virgula." r36_estado = '$this->r36_estado' ";
       $virgula = ",";
       if(trim($this->r36_estado) == null ){
         $this->erro_sql = " Campo Estado nao Informado.";
         $this->erro_campo = "r36_estado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r36_dtalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_dtalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r36_dtalt_dia"] !="") ){
       $sql  .= $virgula." r36_dtalt = '$this->r36_dtalt' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["r36_dtalt_dia"])){
         $sql  .= $virgula." r36_dtalt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r36_contr1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_contr1"])){
        if(trim($this->r36_contr1)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r36_contr1"])){
           $this->r36_contr1 = "0" ;
        }
       $sql  .= $virgula." r36_contr1 = $this->r36_contr1 ";
       $virgula = ",";
     }
     if(trim($this->r36_valorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_valorc"])){
        if(trim($this->r36_valorc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r36_valorc"])){
           $this->r36_valorc = "0" ;
        }
       $sql  .= $virgula." r36_valorc = $this->r36_valorc ";
       $virgula = ",";
     }
     if(trim($this->r36_contrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r36_contrato"])){
        if(trim($this->r36_contrato)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r36_contrato"])){
           $this->r36_contrato = "0" ;
        }
       $sql  .= $virgula." r36_contrato = $this->r36_contrato ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r36_anousu!=null){
       $sql .= " r36_anousu = $this->r36_anousu";
     }
     if($r36_mesusu!=null){
       $sql .= " and  r36_mesusu = $this->r36_mesusu";
     }
     if($r36_sequencia!=null){
       $sql .= " and  r36_sequencia = $this->r36_sequencia";
     }
     if($r36_instit!=null){
       $sql .= " and  r36_instit = $this->r36_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r36_anousu,$this->r36_mesusu,$this->r36_sequencia,$this->r36_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4021,'$this->r36_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4022,'$this->r36_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,9578,'$this->r36_sequencia','A')");
         $resac = db_query("insert into db_acountkey values($acount,9895,'$this->r36_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_instit"]))
           $resac = db_query("insert into db_acount values($acount,562,9895,'".AddSlashes(pg_result($resaco,$conresaco,'r36_instit'))."','$this->r36_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_anousu"]))
           $resac = db_query("insert into db_acount values($acount,562,4021,'".AddSlashes(pg_result($resaco,$conresaco,'r36_anousu'))."','$this->r36_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,562,4022,'".AddSlashes(pg_result($resaco,$conresaco,'r36_mesusu'))."','$this->r36_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,562,9578,'".AddSlashes(pg_result($resaco,$conresaco,'r36_sequencia'))."','$this->r36_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,562,4028,'".AddSlashes(pg_result($resaco,$conresaco,'r36_numcgm'))."','$this->r36_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_regist"]))
           $resac = db_query("insert into db_acount values($acount,562,4030,'".AddSlashes(pg_result($resaco,$conresaco,'r36_regist'))."','$this->r36_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_dtvinc"]))
           $resac = db_query("insert into db_acount values($acount,562,4023,'".AddSlashes(pg_result($resaco,$conresaco,'r36_dtvinc'))."','$this->r36_dtvinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_matric"]))
           $resac = db_query("insert into db_acount values($acount,562,4024,'".AddSlashes(pg_result($resaco,$conresaco,'r36_matric'))."','$this->r36_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_estado"]))
           $resac = db_query("insert into db_acount values($acount,562,4025,'".AddSlashes(pg_result($resaco,$conresaco,'r36_estado'))."','$this->r36_estado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_dtalt"]))
           $resac = db_query("insert into db_acount values($acount,562,4026,'".AddSlashes(pg_result($resaco,$conresaco,'r36_dtalt'))."','$this->r36_dtalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_contr1"]))
           $resac = db_query("insert into db_acount values($acount,562,4027,'".AddSlashes(pg_result($resaco,$conresaco,'r36_contr1'))."','$this->r36_contr1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_valorc"]))
           $resac = db_query("insert into db_acount values($acount,562,4029,'".AddSlashes(pg_result($resaco,$conresaco,'r36_valorc'))."','$this->r36_valorc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r36_contrato"]))
           $resac = db_query("insert into db_acount values($acount,562,8870,'".AddSlashes(pg_result($resaco,$conresaco,'r36_contrato'))."','$this->r36_contrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados para I.P.E. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r36_anousu."-".$this->r36_mesusu."-".$this->r36_sequencia."-".$this->r36_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados para I.P.E. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r36_anousu."-".$this->r36_mesusu."-".$this->r36_sequencia."-".$this->r36_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r36_anousu."-".$this->r36_mesusu."-".$this->r36_sequencia."-".$this->r36_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($r36_anousu=null,$r36_mesusu=null,$r36_sequencia=null,$r36_instit=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r36_anousu,$r36_mesusu,$r36_sequencia,$r36_instit));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4021,'$r36_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4022,'$r36_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,9578,'$r36_sequencia','E')");
         $resac = db_query("insert into db_acountkey values($acount,9895,'$r36_instit','E')");
         $resac = db_query("insert into db_acount values($acount,562,9895,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4021,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4022,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,9578,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4028,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4030,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4023,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_dtvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4024,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4025,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_estado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4026,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_dtalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4027,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_contr1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,4029,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_valorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,562,8870,'','".AddSlashes(pg_result($resaco,$iresaco,'r36_contrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ipe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r36_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r36_anousu = $r36_anousu ";
        }
        if($r36_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r36_mesusu = $r36_mesusu ";
        }
        if($r36_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r36_sequencia = $r36_sequencia ";
        }
        if($r36_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r36_instit = $r36_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados para I.P.E. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r36_anousu."-".$r36_mesusu."-".$r36_sequencia."-".$r36_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados para I.P.E. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r36_anousu."-".$r36_mesusu."-".$r36_sequencia."-".$r36_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r36_anousu."-".$r36_mesusu."-".$r36_sequencia."-".$r36_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:ipe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r36_anousu=null,$r36_mesusu=null,$r36_sequencia=null,$r36_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ipe ";
     $sql .= "      inner join db_config  on  db_config.codigo = ipe.r36_instit";
     $sql .= "      inner join rhipe  on  rhipe.rh14_sequencia = ipe.r36_sequencia";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r36_anousu!=null ){
         $sql2 .= " where ipe.r36_anousu = $r36_anousu ";
       }
       if($r36_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " ipe.r36_mesusu = $r36_mesusu ";
       }
       if($r36_sequencia!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " ipe.r36_sequencia = $r36_sequencia ";
       }
       if($r36_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " ipe.r36_instit = $r36_instit ";
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
   function sql_query_arquivo ( $r36_anousu=null,$r36_mesusu=null,$r36_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ipe \n";
     $sql .= "      inner join rhipe        on rhipe.rh14_sequencia       = ipe.r36_sequencia                \n";
     $sql .= "      inner join rhipenumcgm  on rhipenumcgm.rh63_sequencia = rhipe.rh14_sequencia             \n";
     $sql .= "      inner join cgm          on cgm.z01_numcgm             = rhipenumcgm.rh63_numcgm          \n";
     $sql .= "      left  join rhiperegist  on rhiperegist.rh62_sequencia = rhipe.rh14_sequencia             \n";
     $sql .= "      left  join rhpessoal    on rhpessoal.rh01_regist      = rhiperegist.rh62_regist          \n";
     $sql .= "      left  join rhpessoalmov on rhpessoalmov.rh02_regist   = rhiperegist.rh62_regist          \n";
     $sql .= "                             and rhpessoalmov.rh02_anousu   = ipe.r36_anousu                   \n";
     $sql .= "                             and rhpessoalmov.rh02_mesusu   = ipe.r36_mesusu                   \n";
     $sql .= "      left  join rhlota       on rhpessoalmov.rh02_lota     = rhlota.r70_codigo                \n";
		 $sql .= "                             and rhpessoalmov.rh02_instit   = rhlota.r70_instit                \n";
     $sql2 = "";
     if($dbwhere==""){
       if($r36_anousu!=null ){
         $sql2 .= " where ipe.r36_anousu = $r36_anousu ";
       }
       if($r36_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " ipe.r36_mesusu = $r36_mesusu ";
       }
       if($r36_sequencia!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " ipe.r36_sequencia = $r36_regist ";
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

  function sql_query_relatorio_ipergs($iAno, $iMes, $iInstituicao, $sTipo, $lUnificado, $sListaLotacoes) {

    /*
     * Valor default das variaveis sCampo e sCampoValorOrcado
     */
    $sCampo            = " r36_matric, \n";
    $sCampoValorOrcado = " r36_valorc  \n";

    /*
     * Valor default da variavel sWhere
     */
    $sWhere  = " r36_anousu = {$iAno}    \n ";
    $sWhere .= " and r36_mesusu = {$iMes}\n ";

    /*
     * Valor default da variavel sOrder
     */
    $sOrder = "order by z01_nome \n";

    /*
     * Caso o relatório não seja unificado filtramos por instituicao
     */
    if ($lUnificado == 'false') {
      $sWhere .= " and r36_instit in({$iInstituicao}) \n";
    }

    /*
     * Verificamos o tipo de emissão que será utilziado
     * m: Manutenção
     * i: Inclusão
     * t: todos
     * c: cadastro
     *
     * Estão somente implementados os tipos m, i e t
     * **o tipo c possui o mesmo comportamento de t.
     */
    switch ($sTipo) {

     case "m":

      $sCampo = " distinct on (r36_matric) r36_matric, \n";

      /*
       * sWhereAux - where auxiliar para buscar o valor para a variável sCampoValorOrcado
       */
      $sWhereAux  = " ipe.r36_anousu = {$iAno}               \n";
      $sWhereAux .= " and ipe.r36_mesusu = {$iMes}           \n";
      $sWhereAux .= " and ipe.r36_matric = dados.r36_matric  \n";

      if ($lUnificado == 'f' || $lUnificado == 'false') {

        $sWhereAux  = " ipe.r36_anousu = {$iAno}                \n";
        $sWhereAux .= " and ipe.r36_mesusu = {$iMes}            \n";
        $sWhereAux .= " and ipe.r36_instit in ({$iInstituicao}) \n";
        $sWhereAux .= " and ipe.r36_matric = dados.r36_matric   \n";
      }

      $sCampoValorOrcado = $this->sql_query_file(null,null,null,null,"sum(ipe.r36_valorc) as r36_valorc","",$sWhereAux);
      $sWhere .= " and r36_matric > 0 \n";

     break;

     case "i":
       $sWhere .= " and r36_matric = 0 \n";

     break;

    }

    /*
     * Caso tenha sido informada uma lista de lotações
     * retornamo o valor default das variaveis sCampo e sCampoValorOrcado deconsiderando o case do pelo parametro sTipo
     */
    if($sListaLotacoes != ""){
      $sCampo            = " r36_matric, \n";
      $sCampoValorOrcado = " r36_valorc  \n";
      $sWhere           .= " and r70_estrut::integer in ({$sListaLotacoes}) \n";
      $sOrder            = " order by r70_estrut,z01_nome \n";
    }

    $sCampos  = $sCampo;
    $sCampos .= " z01_nome,                  \n";
    $sCampos .= " case                       \n";
    $sCampos .= "   when rh01_regist is null \n";
    $sCampos .= "     then z01_sexo          \n";
    $sCampos .= "   else rh01_sexo           \n";
    $sCampos .= " end as z01_sexo,           \n";
    $sCampos .= " case                       \n";
    $sCampos .= "   when rh01_regist is null \n";
    $sCampos .= "     then z01_estciv        \n";
    $sCampos .= "   else rh01_estciv         \n";
    $sCampos .= " end as z01_estciv,         \n";
    $sCampos .= " case                       \n";
    $sCampos .= "   when rh01_regist is null \n";
    $sCampos .= "     then z01_nasc          \n";
    $sCampos .= "   else rh01_nasc           \n";
    $sCampos .= " end as z01_nasc,           \n";
    $sCampos .= " z01_ident,                 \n";
    $sCampos .= " r36_dtalt,                 \n";
    $sCampos .= " r36_estado,                \n";
    $sCampos .= " r36_valorc,                \n";
    $sCampos .= " r70_estrut                 \n";

    $sSqlDados = $this->sql_query_arquivo(null, null, null, $sCampos, "r36_matric,z01_nome desc", $sWhere);

    $sSql  = " select r36_matric,                          \n";
    $sSql .= "        z01_nome,                            \n";
    $sSql .= "        z01_sexo,                            \n";
    $sSql .= "        z01_estciv,                          \n";
    $sSql .= "        z01_nasc,                            \n";
    $sSql .= "        z01_ident,                           \n";
    $sSql .= "        r36_dtalt,                           \n";
    $sSql .= "        r36_estado,                          \n";
    $sSql .= "        r70_estrut,                          \n";
    $sSql .= "        ({$sCampoValorOrcado}) as r36_valorc \n";
    $sSql .= "   from ({$sSqlDados}) as dados              \n";
    $sSql .= "   {$sOrder}                                 \n";

    return $sSql;

  }

   function sql_query_file ( $r36_anousu=null,$r36_mesusu=null,$r36_sequencia=null,$r36_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ipe ";
     $sql2 = "";
     if($dbwhere==""){
       if($r36_anousu!=null ){
         $sql2 .= " where ipe.r36_anousu = $r36_anousu ";
       }
       if($r36_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " ipe.r36_mesusu = $r36_mesusu ";
       }
       if($r36_sequencia!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " ipe.r36_sequencia = $r36_sequencia ";
       }
       if($r36_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " ipe.r36_instit = $r36_instit ";
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
