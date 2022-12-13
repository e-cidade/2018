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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE sau_procedimento
class cl_sau_procedimento {
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
   var $sd63_i_codigo = 0;
   var $sd63_c_procedimento = null;
   var $sd63_c_nome = null;
   var $sd63_i_complexidade = 0;
   var $sd63_c_sexo = null;
   var $sd63_i_execucaomax = 0;
   var $sd63_i_maxdias = 0;
   var $sd63_i_pontos = 0;
   var $sd63_i_idademin = 0;
   var $sd63_i_idademax = 0;
   var $sd63_f_sh = 0;
   var $sd63_f_sa = 0;
   var $sd63_f_sp = 0;
   var $sd63_i_financiamento = 0;
   var $sd63_i_rubrica = 0;
   var $sd63_i_anocomp = 0;
   var $sd63_i_mescomp = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd63_i_codigo = int8 = Código
                 sd63_c_procedimento = varchar(10) = Procedimento
                 sd63_c_nome = varchar(250) = Nome
                 sd63_i_complexidade = int8 = Complexidade
                 sd63_c_sexo = varchar(1) = Sexo
                 sd63_i_execucaomax = int8 = Execução Maxima
                 sd63_i_maxdias = int8 = Dias em Permanencia
                 sd63_i_pontos = int4 = Pontos
                 sd63_i_idademin = int8 = Idade Min.
                 sd63_i_idademax = int8 = Idade Max.
                 sd63_f_sh = float8 = Valor do Serviço Hospitalar
                 sd63_f_sa = float8 = Valor do Serviço Ambulatorial
                 sd63_f_sp = float8 = Valor do Serviço Profissional
                 sd63_i_financiamento = int8 = Financiamento
                 sd63_i_rubrica = int4 = Rubrica
                 sd63_i_anocomp = int4 = Ano
                 sd63_i_mescomp = int4 = Mes
                 ";
   //funcao construtor da classe
   function cl_sau_procedimento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_procedimento");
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
       $this->sd63_i_codigo = ($this->sd63_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_codigo"]:$this->sd63_i_codigo);
       $this->sd63_c_procedimento = ($this->sd63_c_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_c_procedimento"]:$this->sd63_c_procedimento);
       $this->sd63_c_nome = ($this->sd63_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_c_nome"]:$this->sd63_c_nome);
       $this->sd63_i_complexidade = ($this->sd63_i_complexidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_complexidade"]:$this->sd63_i_complexidade);
       $this->sd63_c_sexo = ($this->sd63_c_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_c_sexo"]:$this->sd63_c_sexo);
       $this->sd63_i_execucaomax = ($this->sd63_i_execucaomax == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_execucaomax"]:$this->sd63_i_execucaomax);
       $this->sd63_i_maxdias = ($this->sd63_i_maxdias == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_maxdias"]:$this->sd63_i_maxdias);
       $this->sd63_i_pontos = ($this->sd63_i_pontos == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_pontos"]:$this->sd63_i_pontos);
       $this->sd63_i_idademin = ($this->sd63_i_idademin == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_idademin"]:$this->sd63_i_idademin);
       $this->sd63_i_idademax = ($this->sd63_i_idademax == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_idademax"]:$this->sd63_i_idademax);
       $this->sd63_f_sh = ($this->sd63_f_sh == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_f_sh"]:$this->sd63_f_sh);
       $this->sd63_f_sa = ($this->sd63_f_sa == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_f_sa"]:$this->sd63_f_sa);
       $this->sd63_f_sp = ($this->sd63_f_sp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_f_sp"]:$this->sd63_f_sp);
       $this->sd63_i_financiamento = ($this->sd63_i_financiamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_financiamento"]:$this->sd63_i_financiamento);
       $this->sd63_i_rubrica = ($this->sd63_i_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_rubrica"]:$this->sd63_i_rubrica);
       $this->sd63_i_anocomp = ($this->sd63_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_anocomp"]:$this->sd63_i_anocomp);
       $this->sd63_i_mescomp = ($this->sd63_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_mescomp"]:$this->sd63_i_mescomp);
     }else{
       $this->sd63_i_codigo = ($this->sd63_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd63_i_codigo"]:$this->sd63_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd63_i_codigo){
      $this->atualizacampos();
     if($this->sd63_c_procedimento == null ){
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "sd63_c_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_c_nome == null ){
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "sd63_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_complexidade == null ){
       $this->erro_sql = " Campo Complexidade nao Informado.";
       $this->erro_campo = "sd63_i_complexidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_c_sexo == null ){
       $this->erro_sql = " Campo Sexo nao Informado.";
       $this->erro_campo = "sd63_c_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_execucaomax == null ){
       $this->erro_sql = " Campo Execução Maxima nao Informado.";
       $this->erro_campo = "sd63_i_execucaomax";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_maxdias == null ){
       $this->erro_sql = " Campo Dias em Permanencia nao Informado.";
       $this->erro_campo = "sd63_i_maxdias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_pontos == null ){
       $this->erro_sql = " Campo Pontos nao Informado.";
       $this->erro_campo = "sd63_i_pontos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_idademin == null ){
       $this->erro_sql = " Campo Idade Min. nao Informado.";
       $this->erro_campo = "sd63_i_idademin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_idademax == null ){
       $this->erro_sql = " Campo Idade Max. nao Informado.";
       $this->erro_campo = "sd63_i_idademax";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_f_sh == null ){
       $this->erro_sql = " Campo Valor do Serviço Hospitalar nao Informado.";
       $this->erro_campo = "sd63_f_sh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_f_sa == null ){
       $this->erro_sql = " Campo Valor do Serviço Ambulatorial nao Informado.";
       $this->erro_campo = "sd63_f_sa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_f_sp == null ){
       $this->erro_sql = " Campo Valor do Serviço Profissional nao Informado.";
       $this->erro_campo = "sd63_f_sp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_financiamento == null ){
       $this->erro_sql = " Campo Financiamento nao Informado.";
       $this->erro_campo = "sd63_i_financiamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_rubrica == null ){
       $this->sd63_i_rubrica = "null";
     }
     if($this->sd63_i_anocomp == null ){
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd63_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd63_i_mescomp == null ){
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd63_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd63_i_codigo == "" || $sd63_i_codigo == null ){
       $result = db_query("select nextval('sau_procedimento_sd63_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_procedimento_sd63_i_codigo_seq do campo: sd63_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd63_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sau_procedimento_sd63_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd63_i_codigo)){
         $this->erro_sql = " Campo sd63_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd63_i_codigo = $sd63_i_codigo;
       }
     }
     if(($this->sd63_i_codigo == null) || ($this->sd63_i_codigo == "") ){
       $this->erro_sql = " Campo sd63_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_procedimento(
                                       sd63_i_codigo
                                      ,sd63_c_procedimento
                                      ,sd63_c_nome
                                      ,sd63_i_complexidade
                                      ,sd63_c_sexo
                                      ,sd63_i_execucaomax
                                      ,sd63_i_maxdias
                                      ,sd63_i_pontos
                                      ,sd63_i_idademin
                                      ,sd63_i_idademax
                                      ,sd63_f_sh
                                      ,sd63_f_sa
                                      ,sd63_f_sp
                                      ,sd63_i_financiamento
                                      ,sd63_i_rubrica
                                      ,sd63_i_anocomp
                                      ,sd63_i_mescomp
                       )
                values (
                                $this->sd63_i_codigo
                               ,'$this->sd63_c_procedimento'
                               ,'$this->sd63_c_nome'
                               ,$this->sd63_i_complexidade
                               ,'$this->sd63_c_sexo'
                               ,$this->sd63_i_execucaomax
                               ,$this->sd63_i_maxdias
                               ,$this->sd63_i_pontos
                               ,$this->sd63_i_idademin
                               ,$this->sd63_i_idademax
                               ,$this->sd63_f_sh
                               ,$this->sd63_f_sa
                               ,$this->sd63_f_sp
                               ,$this->sd63_i_financiamento
                               ,$this->sd63_i_rubrica
                               ,$this->sd63_i_anocomp
                               ,$this->sd63_i_mescomp
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimentos ($this->sd63_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimentos ($this->sd63_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd63_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd63_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11553,'$this->sd63_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1988,11553,'','".AddSlashes(pg_result($resaco,0,'sd63_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11554,'','".AddSlashes(pg_result($resaco,0,'sd63_c_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11555,'','".AddSlashes(pg_result($resaco,0,'sd63_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11556,'','".AddSlashes(pg_result($resaco,0,'sd63_i_complexidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11557,'','".AddSlashes(pg_result($resaco,0,'sd63_c_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11558,'','".AddSlashes(pg_result($resaco,0,'sd63_i_execucaomax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11559,'','".AddSlashes(pg_result($resaco,0,'sd63_i_maxdias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11560,'','".AddSlashes(pg_result($resaco,0,'sd63_i_pontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11561,'','".AddSlashes(pg_result($resaco,0,'sd63_i_idademin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11562,'','".AddSlashes(pg_result($resaco,0,'sd63_i_idademax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11563,'','".AddSlashes(pg_result($resaco,0,'sd63_f_sh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11564,'','".AddSlashes(pg_result($resaco,0,'sd63_f_sa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11565,'','".AddSlashes(pg_result($resaco,0,'sd63_f_sp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11566,'','".AddSlashes(pg_result($resaco,0,'sd63_i_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11567,'','".AddSlashes(pg_result($resaco,0,'sd63_i_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11568,'','".AddSlashes(pg_result($resaco,0,'sd63_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1988,11569,'','".AddSlashes(pg_result($resaco,0,'sd63_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($sd63_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sau_procedimento set ";
     $virgula = "";
     if(trim($this->sd63_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_codigo"])){
       $sql  .= $virgula." sd63_i_codigo = $this->sd63_i_codigo ";
       $virgula = ",";
       if(trim($this->sd63_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd63_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_c_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_c_procedimento"])){
       $sql  .= $virgula." sd63_c_procedimento = '$this->sd63_c_procedimento' ";
       $virgula = ",";
       if(trim($this->sd63_c_procedimento) == null ){
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "sd63_c_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_c_nome"])){
       $sql  .= $virgula." sd63_c_nome = '$this->sd63_c_nome' ";
       $virgula = ",";
       if(trim($this->sd63_c_nome) == null ){
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "sd63_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_complexidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_complexidade"])){
       $sql  .= $virgula." sd63_i_complexidade = $this->sd63_i_complexidade ";
       $virgula = ",";
       if(trim($this->sd63_i_complexidade) == null ){
         $this->erro_sql = " Campo Complexidade nao Informado.";
         $this->erro_campo = "sd63_i_complexidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_c_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_c_sexo"])){
       $sql  .= $virgula." sd63_c_sexo = '$this->sd63_c_sexo' ";
       $virgula = ",";
       if(trim($this->sd63_c_sexo) == null ){
         $this->erro_sql = " Campo Sexo nao Informado.";
         $this->erro_campo = "sd63_c_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_execucaomax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_execucaomax"])){
       $sql  .= $virgula." sd63_i_execucaomax = $this->sd63_i_execucaomax ";
       $virgula = ",";
       if(trim($this->sd63_i_execucaomax) == null ){
         $this->erro_sql = " Campo Execução Maxima nao Informado.";
         $this->erro_campo = "sd63_i_execucaomax";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_maxdias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_maxdias"])){
       $sql  .= $virgula." sd63_i_maxdias = $this->sd63_i_maxdias ";
       $virgula = ",";
       if(trim($this->sd63_i_maxdias) == null ){
         $this->erro_sql = " Campo Dias em Permanencia nao Informado.";
         $this->erro_campo = "sd63_i_maxdias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_pontos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_pontos"])){
       $sql  .= $virgula." sd63_i_pontos = $this->sd63_i_pontos ";
       $virgula = ",";
       if(trim($this->sd63_i_pontos) == null ){
         $this->erro_sql = " Campo Pontos nao Informado.";
         $this->erro_campo = "sd63_i_pontos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_idademin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_idademin"])){
       $sql  .= $virgula." sd63_i_idademin = $this->sd63_i_idademin ";
       $virgula = ",";
       if(trim($this->sd63_i_idademin) == null ){
         $this->erro_sql = " Campo Idade Min. nao Informado.";
         $this->erro_campo = "sd63_i_idademin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_idademax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_idademax"])){
       $sql  .= $virgula." sd63_i_idademax = $this->sd63_i_idademax ";
       $virgula = ",";
       if(trim($this->sd63_i_idademax) == null ){
         $this->erro_sql = " Campo Idade Max. nao Informado.";
         $this->erro_campo = "sd63_i_idademax";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_f_sh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_f_sh"])){
       $sql  .= $virgula." sd63_f_sh = $this->sd63_f_sh ";
       $virgula = ",";
       if(trim($this->sd63_f_sh) == null ){
         $this->erro_sql = " Campo Valor do Serviço Hospitalar nao Informado.";
         $this->erro_campo = "sd63_f_sh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_f_sa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_f_sa"])){
       $sql  .= $virgula." sd63_f_sa = $this->sd63_f_sa ";
       $virgula = ",";
       if(trim($this->sd63_f_sa) == null ){
         $this->erro_sql = " Campo Valor do Serviço Ambulatorial nao Informado.";
         $this->erro_campo = "sd63_f_sa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_f_sp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_f_sp"])){
       $sql  .= $virgula." sd63_f_sp = $this->sd63_f_sp ";
       $virgula = ",";
       if(trim($this->sd63_f_sp) == null ){
         $this->erro_sql = " Campo Valor do Serviço Profissional nao Informado.";
         $this->erro_campo = "sd63_f_sp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_financiamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_financiamento"])){
       $sql  .= $virgula." sd63_i_financiamento = $this->sd63_i_financiamento ";
       $virgula = ",";
       if(trim($this->sd63_i_financiamento) == null ){
         $this->erro_sql = " Campo Financiamento nao Informado.";
         $this->erro_campo = "sd63_i_financiamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_rubrica"])){
        if(trim($this->sd63_i_rubrica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_rubrica"])){
           $this->sd63_i_rubrica = "0" ;
        }
       $sql  .= $virgula." sd63_i_rubrica = $this->sd63_i_rubrica ";
       $virgula = ",";
     }
     if(trim($this->sd63_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_anocomp"])){
       $sql  .= $virgula." sd63_i_anocomp = $this->sd63_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd63_i_anocomp) == null ){
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd63_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd63_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_mescomp"])){
       $sql  .= $virgula." sd63_i_mescomp = $this->sd63_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd63_i_mescomp) == null ){
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd63_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd63_i_codigo!=null){
       $sql .= " sd63_i_codigo = $this->sd63_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd63_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11553,'$this->sd63_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_codigo"]) || $this->sd63_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1988,11553,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_codigo'))."','$this->sd63_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_c_procedimento"]) || $this->sd63_c_procedimento != "")
           $resac = db_query("insert into db_acount values($acount,1988,11554,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_c_procedimento'))."','$this->sd63_c_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_c_nome"]) || $this->sd63_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,1988,11555,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_c_nome'))."','$this->sd63_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_complexidade"]) || $this->sd63_i_complexidade != "")
           $resac = db_query("insert into db_acount values($acount,1988,11556,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_complexidade'))."','$this->sd63_i_complexidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_c_sexo"]) || $this->sd63_c_sexo != "")
           $resac = db_query("insert into db_acount values($acount,1988,11557,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_c_sexo'))."','$this->sd63_c_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_execucaomax"]) || $this->sd63_i_execucaomax != "")
           $resac = db_query("insert into db_acount values($acount,1988,11558,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_execucaomax'))."','$this->sd63_i_execucaomax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_maxdias"]) || $this->sd63_i_maxdias != "")
           $resac = db_query("insert into db_acount values($acount,1988,11559,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_maxdias'))."','$this->sd63_i_maxdias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_pontos"]) || $this->sd63_i_pontos != "")
           $resac = db_query("insert into db_acount values($acount,1988,11560,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_pontos'))."','$this->sd63_i_pontos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_idademin"]) || $this->sd63_i_idademin != "")
           $resac = db_query("insert into db_acount values($acount,1988,11561,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_idademin'))."','$this->sd63_i_idademin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_idademax"]) || $this->sd63_i_idademax != "")
           $resac = db_query("insert into db_acount values($acount,1988,11562,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_idademax'))."','$this->sd63_i_idademax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_f_sh"]) || $this->sd63_f_sh != "")
           $resac = db_query("insert into db_acount values($acount,1988,11563,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_f_sh'))."','$this->sd63_f_sh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_f_sa"]) || $this->sd63_f_sa != "")
           $resac = db_query("insert into db_acount values($acount,1988,11564,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_f_sa'))."','$this->sd63_f_sa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_f_sp"]) || $this->sd63_f_sp != "")
           $resac = db_query("insert into db_acount values($acount,1988,11565,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_f_sp'))."','$this->sd63_f_sp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_financiamento"]) || $this->sd63_i_financiamento != "")
           $resac = db_query("insert into db_acount values($acount,1988,11566,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_financiamento'))."','$this->sd63_i_financiamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_rubrica"]) || $this->sd63_i_rubrica != "")
           $resac = db_query("insert into db_acount values($acount,1988,11567,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_rubrica'))."','$this->sd63_i_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_anocomp"]) || $this->sd63_i_anocomp != "")
           $resac = db_query("insert into db_acount values($acount,1988,11568,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_anocomp'))."','$this->sd63_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd63_i_mescomp"]) || $this->sd63_i_mescomp != "")
           $resac = db_query("insert into db_acount values($acount,1988,11569,'".AddSlashes(pg_result($resaco,$conresaco,'sd63_i_mescomp'))."','$this->sd63_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd63_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd63_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd63_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($sd63_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd63_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11553,'$sd63_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1988,11553,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11554,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_c_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11555,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11556,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_complexidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11557,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_c_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11558,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_execucaomax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11559,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_maxdias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11560,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_pontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11561,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_idademin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11562,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_idademax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11563,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_f_sh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11564,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_f_sa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11565,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_f_sp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11566,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11567,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11568,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1988,11569,'','".AddSlashes(pg_result($resaco,$iresaco,'sd63_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_procedimento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd63_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd63_i_codigo = $sd63_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd63_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd63_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd63_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_procedimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $sd63_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_procedimento ";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left  join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      inner join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql2 = "";
     if($dbwhere==""){
       if($sd63_i_codigo!=null ){
         $sql2 .= " where sau_procedimento.sd63_i_codigo = $sd63_i_codigo ";
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
   function sql_query_file ( $sd63_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_procedimento ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd63_i_codigo!=null ){
         $sql2 .= " where sau_procedimento.sd63_i_codigo = $sd63_i_codigo ";
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

   public function sql_query_cbo_compativel( $sd63_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

     $sql  = "select {$campos} ";
     $sql .= "  from sau_procedimento ";
     $sql .= " inner join sau_proccbo on sd96_i_procedimento = sd63_i_codigo";
     $sql .= " inner join rhcbo       on rh70_sequencial     = sd96_i_cbo";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd63_i_codigo)){
         $sql2 .= " where sau_procedimento.sd63_i_codigo = $sd63_i_codigo ";
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