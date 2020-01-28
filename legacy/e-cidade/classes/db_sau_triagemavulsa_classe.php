<?php
/**
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
//CLASSE DA ENTIDADE sau_triagemavulsa
class cl_sau_triagemavulsa { 
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
   var $s152_i_codigo = 0; 
   var $s152_i_cbosprofissional = 0; 
   var $s152_i_cgsund = 0; 
   var $s152_i_login = 0; 
   var $s152_i_pressaosistolica = 0; 
   var $s152_i_pressaodiastolica = 0; 
   var $s152_i_cintura = 0; 
   var $s152_n_peso = 0; 
   var $s152_i_altura = 0; 
   var $s152_i_glicemia = 0; 
   var $s152_i_alimentacaoexameglicemia = 0; 
   var $s152_d_dataconsulta_dia = null; 
   var $s152_d_dataconsulta_mes = null; 
   var $s152_d_dataconsulta_ano = null; 
   var $s152_d_dataconsulta = null; 
   var $s152_d_datasistema_dia = null; 
   var $s152_d_datasistema_mes = null; 
   var $s152_d_datasistema_ano = null; 
   var $s152_d_datasistema = null; 
   var $s152_c_horasistema = null; 
   var $s152_n_temperatura = 0; 
   var $s152_evolucao = null; 
   var $s152_perimetrocefalico = null;
   var $s152_frequenciarespiratoria = null;
   var $s152_frequenciacardiaca = null;
   var $s152_dum_dia = null; 
   var $s152_dum_mes = null; 
   var $s152_dum_ano = null; 
   var $s152_dum = null; 
   var $s152_saturacao = null;
   var $s152_subjetivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s152_i_codigo = int4 = Código 
                 s152_i_cbosprofissional = int4 = CBOS 
                 s152_i_cgsund = int4 = CGS 
                 s152_i_login = int4 = Login 
                 s152_i_pressaosistolica = int4 = Sistólica 
                 s152_i_pressaodiastolica = int4 = Diastólica 
                 s152_i_cintura = int4 = Cintura 
                 s152_n_peso = float4 = Peso 
                 s152_i_altura = int4 = Altura 
                 s152_i_glicemia = int4 = Exame Glicemia (MG/D) 
                 s152_i_alimentacaoexameglicemia = int4 = Momento da Coleta 
                 s152_d_dataconsulta = date = Data da consulta 
                 s152_d_datasistema = date = Data do sistema 
                 s152_c_horasistema = char(5) = Hora do sistema 
                 s152_n_temperatura = float4 = Temperatura 
                 s152_evolucao = text = Objetivo 
                 s152_perimetrocefalico = int4 = Perímetro Cefálico 
                 s152_frequenciarespiratoria = int4 = Frequência Respiratória 
                 s152_frequenciacardiaca = int4 = Frequência Cardíaca 
                 s152_dum = date = DUM 
                 s152_saturacao = int4 = Saturação de O2 
                 s152_subjetivo = text = Subjetivo 
                 ";
   //funcao construtor da classe 
   function cl_sau_triagemavulsa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_triagemavulsa"); 
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
       $this->s152_i_codigo = ($this->s152_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_codigo"]:$this->s152_i_codigo);
       $this->s152_i_cbosprofissional = ($this->s152_i_cbosprofissional == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_cbosprofissional"]:$this->s152_i_cbosprofissional);
       $this->s152_i_cgsund = ($this->s152_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_cgsund"]:$this->s152_i_cgsund);
       $this->s152_i_login = ($this->s152_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_login"]:$this->s152_i_login);
       $this->s152_i_pressaosistolica = ($this->s152_i_pressaosistolica == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_pressaosistolica"]:$this->s152_i_pressaosistolica);
       $this->s152_i_pressaodiastolica = ($this->s152_i_pressaodiastolica == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_pressaodiastolica"]:$this->s152_i_pressaodiastolica);
       $this->s152_i_cintura = ($this->s152_i_cintura == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_cintura"]:$this->s152_i_cintura);
       $this->s152_n_peso = ($this->s152_n_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_n_peso"]:$this->s152_n_peso);
       $this->s152_i_altura = ($this->s152_i_altura == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_altura"]:$this->s152_i_altura);
       $this->s152_i_glicemia = ($this->s152_i_glicemia == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_glicemia"]:$this->s152_i_glicemia);
       $this->s152_i_alimentacaoexameglicemia = ($this->s152_i_alimentacaoexameglicemia == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_alimentacaoexameglicemia"]:$this->s152_i_alimentacaoexameglicemia);
       if($this->s152_d_dataconsulta == ""){
         $this->s152_d_dataconsulta_dia = ($this->s152_d_dataconsulta_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_d_dataconsulta_dia"]:$this->s152_d_dataconsulta_dia);
         $this->s152_d_dataconsulta_mes = ($this->s152_d_dataconsulta_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_d_dataconsulta_mes"]:$this->s152_d_dataconsulta_mes);
         $this->s152_d_dataconsulta_ano = ($this->s152_d_dataconsulta_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_d_dataconsulta_ano"]:$this->s152_d_dataconsulta_ano);
         if($this->s152_d_dataconsulta_dia != ""){
            $this->s152_d_dataconsulta = $this->s152_d_dataconsulta_ano."-".$this->s152_d_dataconsulta_mes."-".$this->s152_d_dataconsulta_dia;
         }
       }
       if($this->s152_d_datasistema == ""){
         $this->s152_d_datasistema_dia = ($this->s152_d_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_d_datasistema_dia"]:$this->s152_d_datasistema_dia);
         $this->s152_d_datasistema_mes = ($this->s152_d_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_d_datasistema_mes"]:$this->s152_d_datasistema_mes);
         $this->s152_d_datasistema_ano = ($this->s152_d_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_d_datasistema_ano"]:$this->s152_d_datasistema_ano);
         if($this->s152_d_datasistema_dia != ""){
            $this->s152_d_datasistema = $this->s152_d_datasistema_ano."-".$this->s152_d_datasistema_mes."-".$this->s152_d_datasistema_dia;
         }
       }
       $this->s152_c_horasistema = ($this->s152_c_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_c_horasistema"]:$this->s152_c_horasistema);
       $this->s152_n_temperatura = ($this->s152_n_temperatura == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_n_temperatura"]:$this->s152_n_temperatura);
       $this->s152_evolucao = ($this->s152_evolucao == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_evolucao"]:$this->s152_evolucao);
       $this->s152_perimetrocefalico = ($this->s152_perimetrocefalico == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_perimetrocefalico"]:$this->s152_perimetrocefalico);
       $this->s152_frequenciarespiratoria = ($this->s152_frequenciarespiratoria == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_frequenciarespiratoria"]:$this->s152_frequenciarespiratoria);
       $this->s152_frequenciacardiaca = ($this->s152_frequenciacardiaca == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_frequenciacardiaca"]:$this->s152_frequenciacardiaca);
       if($this->s152_dum == ""){
         $this->s152_dum_dia = ($this->s152_dum_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_dum_dia"]:$this->s152_dum_dia);
         $this->s152_dum_mes = ($this->s152_dum_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_dum_mes"]:$this->s152_dum_mes);
         $this->s152_dum_ano = ($this->s152_dum_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_dum_ano"]:$this->s152_dum_ano);
         if($this->s152_dum_dia != ""){
            $this->s152_dum = $this->s152_dum_ano."-".$this->s152_dum_mes."-".$this->s152_dum_dia;
         }
       }
       $this->s152_saturacao = ($this->s152_saturacao == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_saturacao"]:$this->s152_saturacao);
       $this->s152_subjetivo = ($this->s152_subjetivo == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_subjetivo"]:$this->s152_subjetivo);
     }else{
       $this->s152_i_codigo = ($this->s152_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s152_i_codigo"]:$this->s152_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($s152_i_codigo){ 
      $this->atualizacampos();
     if($this->s152_i_cbosprofissional == null ){ 
       $this->erro_sql = " Campo CBOS não informado.";
       $this->erro_campo = "s152_i_cbosprofissional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s152_i_cgsund == null ){ 
       $this->erro_sql = " Campo CGS não informado.";
       $this->erro_campo = "s152_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s152_i_login == null ){ 
       $this->erro_sql = " Campo Login não informado.";
       $this->erro_campo = "s152_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s152_i_pressaosistolica == null ){ 
       $this->s152_i_pressaosistolica = "null";
     }
     if($this->s152_i_pressaodiastolica == null ){ 
       $this->s152_i_pressaodiastolica = "null";
     }
     if($this->s152_i_cintura == null ){ 
       $this->s152_i_cintura = "null";
     }
     if($this->s152_n_peso == null ){ 
       $this->s152_n_peso = "null";
     }
     if($this->s152_i_altura == null ){ 
       $this->s152_i_altura = "null";
     }
     if($this->s152_i_glicemia == null ){ 
       $this->s152_i_glicemia = "null";
     }
     if($this->s152_i_alimentacaoexameglicemia == null ){ 
       $this->s152_i_alimentacaoexameglicemia = "null";
     }
     if($this->s152_d_dataconsulta == null ){ 
       $this->erro_sql = " Campo Data da consulta não informado.";
       $this->erro_campo = "s152_d_dataconsulta_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s152_d_datasistema == null ){ 
       $this->erro_sql = " Campo Data do sistema não informado.";
       $this->erro_campo = "s152_d_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s152_c_horasistema == null ){ 
       $this->erro_sql = " Campo Hora do sistema não informado.";
       $this->erro_campo = "s152_c_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s152_n_temperatura == null ){ 
       $this->s152_n_temperatura = "null";
     }
     if($this->s152_perimetrocefalico == null ){ 
       $this->s152_perimetrocefalico = "null";
     }
     if($this->s152_frequenciarespiratoria == null ){ 
       $this->s152_frequenciarespiratoria = "null";
     }
     if($this->s152_frequenciacardiaca == null ){ 
       $this->s152_frequenciacardiaca = "null";
     }
     if($this->s152_dum == null ){ 
       $this->s152_dum = "null";
     }
     if($this->s152_saturacao == null ){ 
       $this->s152_saturacao = "null";
     }
     if($s152_i_codigo == "" || $s152_i_codigo == null ){
       $result = db_query("select nextval('sau_triagemavulsa_s152_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_triagemavulsa_s152_i_codigo_seq do campo: s152_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s152_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_triagemavulsa_s152_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s152_i_codigo)){
         $this->erro_sql = " Campo s152_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s152_i_codigo = $s152_i_codigo; 
       }
     }
     if(($this->s152_i_codigo == null) || ($this->s152_i_codigo == "") ){ 
       $this->erro_sql = " Campo s152_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_triagemavulsa(
                                       s152_i_codigo 
                                      ,s152_i_cbosprofissional 
                                      ,s152_i_cgsund 
                                      ,s152_i_login 
                                      ,s152_i_pressaosistolica 
                                      ,s152_i_pressaodiastolica 
                                      ,s152_i_cintura 
                                      ,s152_n_peso 
                                      ,s152_i_altura 
                                      ,s152_i_glicemia 
                                      ,s152_i_alimentacaoexameglicemia 
                                      ,s152_d_dataconsulta 
                                      ,s152_d_datasistema 
                                      ,s152_c_horasistema 
                                      ,s152_n_temperatura 
                                      ,s152_evolucao 
                                      ,s152_perimetrocefalico 
                                      ,s152_frequenciarespiratoria 
                                      ,s152_frequenciacardiaca 
                                      ,s152_dum 
                                      ,s152_saturacao 
                                      ,s152_subjetivo 
                       )
                values (
                                $this->s152_i_codigo 
                               ,$this->s152_i_cbosprofissional 
                               ,$this->s152_i_cgsund 
                               ,$this->s152_i_login 
                               ,$this->s152_i_pressaosistolica 
                               ,$this->s152_i_pressaodiastolica 
                               ,$this->s152_i_cintura 
                               ,$this->s152_n_peso 
                               ,$this->s152_i_altura 
                               ,$this->s152_i_glicemia 
                               ,$this->s152_i_alimentacaoexameglicemia 
                               ,".($this->s152_d_dataconsulta == "null" || $this->s152_d_dataconsulta == ""?"null":"'".$this->s152_d_dataconsulta."'")." 
                               ,".($this->s152_d_datasistema == "null" || $this->s152_d_datasistema == ""?"null":"'".$this->s152_d_datasistema."'")." 
                               ,'$this->s152_c_horasistema' 
                               ,$this->s152_n_temperatura 
                               ,'$this->s152_evolucao' 
                               ,$this->s152_perimetrocefalico 
                               ,$this->s152_frequenciarespiratoria 
                               ,$this->s152_frequenciacardiaca 
                               ,".($this->s152_dum == "null" || $this->s152_dum == ""?"null":"'".$this->s152_dum."'")." 
                               ,$this->s152_saturacao 
                               ,'$this->s152_subjetivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_triagemavulsa ($this->s152_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_triagemavulsa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_triagemavulsa ($this->s152_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s152_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s152_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17212,'$this->s152_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3043,17212,'','".AddSlashes(pg_result($resaco,0,'s152_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17213,'','".AddSlashes(pg_result($resaco,0,'s152_i_cbosprofissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17214,'','".AddSlashes(pg_result($resaco,0,'s152_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17215,'','".AddSlashes(pg_result($resaco,0,'s152_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17216,'','".AddSlashes(pg_result($resaco,0,'s152_i_pressaosistolica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17217,'','".AddSlashes(pg_result($resaco,0,'s152_i_pressaodiastolica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17218,'','".AddSlashes(pg_result($resaco,0,'s152_i_cintura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17219,'','".AddSlashes(pg_result($resaco,0,'s152_n_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17220,'','".AddSlashes(pg_result($resaco,0,'s152_i_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17221,'','".AddSlashes(pg_result($resaco,0,'s152_i_glicemia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17222,'','".AddSlashes(pg_result($resaco,0,'s152_i_alimentacaoexameglicemia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17223,'','".AddSlashes(pg_result($resaco,0,'s152_d_dataconsulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17224,'','".AddSlashes(pg_result($resaco,0,'s152_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17225,'','".AddSlashes(pg_result($resaco,0,'s152_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,17566,'','".AddSlashes(pg_result($resaco,0,'s152_n_temperatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,20973,'','".AddSlashes(pg_result($resaco,0,'s152_evolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,22005,'','".AddSlashes(pg_result($resaco,0,'s152_perimetrocefalico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,22006,'','".AddSlashes(pg_result($resaco,0,'s152_frequenciarespiratoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,22007,'','".AddSlashes(pg_result($resaco,0,'s152_frequenciacardiaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,22009,'','".AddSlashes(pg_result($resaco,0,'s152_dum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,22010,'','".AddSlashes(pg_result($resaco,0,'s152_saturacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3043,22030,'','".AddSlashes(pg_result($resaco,0,'s152_subjetivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($s152_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_triagemavulsa set ";
     $virgula = "";
     if(trim($this->s152_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_codigo"])){ 
       $sql  .= $virgula." s152_i_codigo = $this->s152_i_codigo ";
       $virgula = ",";
       if(trim($this->s152_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "s152_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s152_i_cbosprofissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_cbosprofissional"])){ 
       $sql  .= $virgula." s152_i_cbosprofissional = $this->s152_i_cbosprofissional ";
       $virgula = ",";
       if(trim($this->s152_i_cbosprofissional) == null ){ 
         $this->erro_sql = " Campo CBOS não informado.";
         $this->erro_campo = "s152_i_cbosprofissional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s152_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_cgsund"])){ 
       $sql  .= $virgula." s152_i_cgsund = $this->s152_i_cgsund ";
       $virgula = ",";
       if(trim($this->s152_i_cgsund) == null ){ 
         $this->erro_sql = " Campo CGS não informado.";
         $this->erro_campo = "s152_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s152_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_login"])){ 
       $sql  .= $virgula." s152_i_login = $this->s152_i_login ";
       $virgula = ",";
       if(trim($this->s152_i_login) == null ){ 
         $this->erro_sql = " Campo Login não informado.";
         $this->erro_campo = "s152_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s152_i_pressaosistolica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_pressaosistolica"])){ 
        if(trim($this->s152_i_pressaosistolica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_i_pressaosistolica"])){ 
           $this->s152_i_pressaosistolica = "null" ;
        } 
       $sql  .= $virgula." s152_i_pressaosistolica = $this->s152_i_pressaosistolica ";
       $virgula = ",";
     }
     if(trim($this->s152_i_pressaodiastolica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_pressaodiastolica"])){ 
        if(trim($this->s152_i_pressaodiastolica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_i_pressaodiastolica"])){ 
           $this->s152_i_pressaodiastolica = "null" ;
        } 
       $sql  .= $virgula." s152_i_pressaodiastolica = $this->s152_i_pressaodiastolica ";
       $virgula = ",";
     }
     if(trim($this->s152_i_cintura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_cintura"])){ 
        if(trim($this->s152_i_cintura)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_i_cintura"])){ 
           $this->s152_i_cintura = "null" ;
        } 
       $sql  .= $virgula." s152_i_cintura = $this->s152_i_cintura ";
       $virgula = ",";
     }
     if(trim($this->s152_n_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_n_peso"])){ 
        if(trim($this->s152_n_peso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_n_peso"])){ 
           $this->s152_n_peso = "null" ;
        } 
       $sql  .= $virgula." s152_n_peso = $this->s152_n_peso ";
       $virgula = ",";
     }
     if(trim($this->s152_i_altura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_altura"])){ 
        if(trim($this->s152_i_altura)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_i_altura"])){ 
           $this->s152_i_altura = "null" ;
        } 
       $sql  .= $virgula." s152_i_altura = $this->s152_i_altura ";
       $virgula = ",";
     }
     if(trim($this->s152_i_glicemia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_glicemia"])){ 
        if(trim($this->s152_i_glicemia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_i_glicemia"])){ 
           $this->s152_i_glicemia = "0" ; 
        } 
       $sql  .= $virgula." s152_i_glicemia = $this->s152_i_glicemia ";
       $virgula = ",";
     }
     if(trim($this->s152_i_alimentacaoexameglicemia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_i_alimentacaoexameglicemia"])){ 
        if(trim($this->s152_i_alimentacaoexameglicemia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_i_alimentacaoexameglicemia"])){ 
           $this->s152_i_alimentacaoexameglicemia = "null" ;
        } 
       $sql  .= $virgula." s152_i_alimentacaoexameglicemia = $this->s152_i_alimentacaoexameglicemia ";
       $virgula = ",";
     }
     if(trim($this->s152_d_dataconsulta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_d_dataconsulta_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s152_d_dataconsulta_dia"] !="") ){ 
       $sql  .= $virgula." s152_d_dataconsulta = '$this->s152_d_dataconsulta' ";
       $virgula = ",";
       if(trim($this->s152_d_dataconsulta) == null ){ 
         $this->erro_sql = " Campo Data da consulta não informado.";
         $this->erro_campo = "s152_d_dataconsulta_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s152_d_dataconsulta_dia"])){ 
         $sql  .= $virgula." s152_d_dataconsulta = null ";
         $virgula = ",";
         if(trim($this->s152_d_dataconsulta) == null ){ 
           $this->erro_sql = " Campo Data da consulta não informado.";
           $this->erro_campo = "s152_d_dataconsulta_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s152_d_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_d_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s152_d_datasistema_dia"] !="") ){ 
       $sql  .= $virgula." s152_d_datasistema = '$this->s152_d_datasistema' ";
       $virgula = ",";
       if(trim($this->s152_d_datasistema) == null ){ 
         $this->erro_sql = " Campo Data do sistema não informado.";
         $this->erro_campo = "s152_d_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s152_d_datasistema_dia"])){ 
         $sql  .= $virgula." s152_d_datasistema = null ";
         $virgula = ",";
         if(trim($this->s152_d_datasistema) == null ){ 
           $this->erro_sql = " Campo Data do sistema não informado.";
           $this->erro_campo = "s152_d_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s152_c_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_c_horasistema"])){ 
       $sql  .= $virgula." s152_c_horasistema = '$this->s152_c_horasistema' ";
       $virgula = ",";
       if(trim($this->s152_c_horasistema) == null ){ 
         $this->erro_sql = " Campo Hora do sistema não informado.";
         $this->erro_campo = "s152_c_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s152_n_temperatura)!=""){

       $sql  .= $virgula." s152_n_temperatura = $this->s152_n_temperatura ";
       $virgula = ",";
     }
     if(    $this->s152_n_temperatura == null
       || ( isset($GLOBALS["HTTP_POST_VARS"]["s152_n_temperatura"]) && $GLOBALS["HTTP_POST_VARS"]["s152_n_temperatura"] == null ) ) {

       $this->s152_n_temperatura = "null";
       $sql  .= $virgula." s152_n_temperatura = $this->s152_n_temperatura ";
       $virgula = ",";
     }
     if(trim($this->s152_evolucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_evolucao"])){ 
       $sql  .= $virgula." s152_evolucao = '$this->s152_evolucao' ";
       $virgula = ",";
     }
     if(trim($this->s152_perimetrocefalico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_perimetrocefalico"])){ 
        if(trim($this->s152_perimetrocefalico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_perimetrocefalico"])){ 
           $this->s152_perimetrocefalico = "null" ;
        } 
       $sql  .= $virgula." s152_perimetrocefalico = $this->s152_perimetrocefalico ";
       $virgula = ",";
     }
     if(trim($this->s152_frequenciarespiratoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_frequenciarespiratoria"])){ 
        if(trim($this->s152_frequenciarespiratoria)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_frequenciarespiratoria"])){ 
           $this->s152_frequenciarespiratoria = "null" ;
        } 
       $sql  .= $virgula." s152_frequenciarespiratoria = $this->s152_frequenciarespiratoria ";
       $virgula = ",";
     }
     if(trim($this->s152_frequenciacardiaca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_frequenciacardiaca"])){ 
        if(trim($this->s152_frequenciacardiaca)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_frequenciacardiaca"])){ 
           $this->s152_frequenciacardiaca = "null" ;
        } 
       $sql  .= $virgula." s152_frequenciacardiaca = $this->s152_frequenciacardiaca ";
       $virgula = ",";
     }
     if(trim($this->s152_dum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_dum_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s152_dum_dia"] !="") ){ 
       $sql  .= $virgula." s152_dum = '$this->s152_dum' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s152_dum_dia"])){ 
         $sql  .= $virgula." s152_dum = null ";
         $virgula = ",";
       }
     }
     if(trim($this->s152_saturacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_saturacao"])){ 
        if(trim($this->s152_saturacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s152_saturacao"])){ 
           $this->s152_saturacao = "null" ;
        } 
       $sql  .= $virgula." s152_saturacao = $this->s152_saturacao ";
       $virgula = ",";
     }
     if(trim($this->s152_subjetivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s152_subjetivo"])){ 
       $sql  .= $virgula." s152_subjetivo = '$this->s152_subjetivo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($s152_i_codigo!=null){
       $sql .= " s152_i_codigo = $this->s152_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s152_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17212,'$this->s152_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_codigo"]) || $this->s152_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3043,17212,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_codigo'))."','$this->s152_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_cbosprofissional"]) || $this->s152_i_cbosprofissional != "")
             $resac = db_query("insert into db_acount values($acount,3043,17213,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_cbosprofissional'))."','$this->s152_i_cbosprofissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_cgsund"]) || $this->s152_i_cgsund != "")
             $resac = db_query("insert into db_acount values($acount,3043,17214,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_cgsund'))."','$this->s152_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_login"]) || $this->s152_i_login != "")
             $resac = db_query("insert into db_acount values($acount,3043,17215,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_login'))."','$this->s152_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_pressaosistolica"]) || $this->s152_i_pressaosistolica != "")
             $resac = db_query("insert into db_acount values($acount,3043,17216,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_pressaosistolica'))."','$this->s152_i_pressaosistolica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_pressaodiastolica"]) || $this->s152_i_pressaodiastolica != "")
             $resac = db_query("insert into db_acount values($acount,3043,17217,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_pressaodiastolica'))."','$this->s152_i_pressaodiastolica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_cintura"]) || $this->s152_i_cintura != "")
             $resac = db_query("insert into db_acount values($acount,3043,17218,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_cintura'))."','$this->s152_i_cintura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_n_peso"]) || $this->s152_n_peso != "")
             $resac = db_query("insert into db_acount values($acount,3043,17219,'".AddSlashes(pg_result($resaco,$conresaco,'s152_n_peso'))."','$this->s152_n_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_altura"]) || $this->s152_i_altura != "")
             $resac = db_query("insert into db_acount values($acount,3043,17220,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_altura'))."','$this->s152_i_altura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_glicemia"]) || $this->s152_i_glicemia != "")
             $resac = db_query("insert into db_acount values($acount,3043,17221,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_glicemia'))."','$this->s152_i_glicemia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_i_alimentacaoexameglicemia"]) || $this->s152_i_alimentacaoexameglicemia != "")
             $resac = db_query("insert into db_acount values($acount,3043,17222,'".AddSlashes(pg_result($resaco,$conresaco,'s152_i_alimentacaoexameglicemia'))."','$this->s152_i_alimentacaoexameglicemia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_d_dataconsulta"]) || $this->s152_d_dataconsulta != "")
             $resac = db_query("insert into db_acount values($acount,3043,17223,'".AddSlashes(pg_result($resaco,$conresaco,'s152_d_dataconsulta'))."','$this->s152_d_dataconsulta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_d_datasistema"]) || $this->s152_d_datasistema != "")
             $resac = db_query("insert into db_acount values($acount,3043,17224,'".AddSlashes(pg_result($resaco,$conresaco,'s152_d_datasistema'))."','$this->s152_d_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_c_horasistema"]) || $this->s152_c_horasistema != "")
             $resac = db_query("insert into db_acount values($acount,3043,17225,'".AddSlashes(pg_result($resaco,$conresaco,'s152_c_horasistema'))."','$this->s152_c_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_n_temperatura"]) || $this->s152_n_temperatura != "")
             $resac = db_query("insert into db_acount values($acount,3043,17566,'".AddSlashes(pg_result($resaco,$conresaco,'s152_n_temperatura'))."','$this->s152_n_temperatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_evolucao"]) || $this->s152_evolucao != "")
             $resac = db_query("insert into db_acount values($acount,3043,20973,'".AddSlashes(pg_result($resaco,$conresaco,'s152_evolucao'))."','$this->s152_evolucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_perimetrocefalico"]) || $this->s152_perimetrocefalico != "")
             $resac = db_query("insert into db_acount values($acount,3043,22005,'".AddSlashes(pg_result($resaco,$conresaco,'s152_perimetrocefalico'))."','$this->s152_perimetrocefalico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_frequenciarespiratoria"]) || $this->s152_frequenciarespiratoria != "")
             $resac = db_query("insert into db_acount values($acount,3043,22006,'".AddSlashes(pg_result($resaco,$conresaco,'s152_frequenciarespiratoria'))."','$this->s152_frequenciarespiratoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_frequenciacardiaca"]) || $this->s152_frequenciacardiaca != "")
             $resac = db_query("insert into db_acount values($acount,3043,22007,'".AddSlashes(pg_result($resaco,$conresaco,'s152_frequenciacardiaca'))."','$this->s152_frequenciacardiaca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_dum"]) || $this->s152_dum != "")
             $resac = db_query("insert into db_acount values($acount,3043,22009,'".AddSlashes(pg_result($resaco,$conresaco,'s152_dum'))."','$this->s152_dum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_saturacao"]) || $this->s152_saturacao != "")
             $resac = db_query("insert into db_acount values($acount,3043,22010,'".AddSlashes(pg_result($resaco,$conresaco,'s152_saturacao'))."','$this->s152_saturacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s152_subjetivo"]) || $this->s152_subjetivo != "")
             $resac = db_query("insert into db_acount values($acount,3043,22030,'".AddSlashes(pg_result($resaco,$conresaco,'s152_subjetivo'))."','$this->s152_subjetivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_triagemavulsa não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s152_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "sau_triagemavulsa não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s152_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s152_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($s152_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($s152_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,17212,'$s152_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3043,17212,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17213,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_cbosprofissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17214,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17215,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17216,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_pressaosistolica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17217,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_pressaodiastolica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17218,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_cintura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17219,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_n_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17220,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17221,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_glicemia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17222,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_i_alimentacaoexameglicemia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17223,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_d_dataconsulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17224,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17225,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,17566,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_n_temperatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,20973,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_evolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,22005,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_perimetrocefalico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,22006,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_frequenciarespiratoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,22007,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_frequenciacardiaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,22009,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_dum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,22010,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_saturacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3043,22030,'','".AddSlashes(pg_result($resaco,$iresaco,'s152_subjetivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sau_triagemavulsa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($s152_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " s152_i_codigo = $s152_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_triagemavulsa não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s152_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "sau_triagemavulsa não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s152_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s152_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_triagemavulsa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($s152_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from sau_triagemavulsa ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_triagemavulsa.s152_i_login";
     $sql .= "      inner join far_cbosprofissional  on  far_cbosprofissional.fa54_i_codigo = sau_triagemavulsa.s152_i_cbosprofissional";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_triagemavulsa.s152_i_cgsund";
     $sql .= "      inner join far_cbos  as a on   a.fa53_i_codigo = far_cbosprofissional.fa54_i_cbos";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = far_cbosprofissional.fa54_i_unidademedico";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as b on   b.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($s152_i_codigo)) {
         $sql2 .= " where sau_triagemavulsa.s152_i_codigo = $s152_i_codigo "; 
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
   public function sql_query_file ($s152_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from sau_triagemavulsa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($s152_i_codigo)){
         $sql2 .= " where sau_triagemavulsa.s152_i_codigo = $s152_i_codigo "; 
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

   /*
   Query utilizada para gerar o grid das triagens avulsa já realizadas
   */
   function sql_query_grid ( $s152_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_triagemavulsa ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_triagemavulsa.s152_i_login";
     $sql .= "      inner join far_cbosprofissional  on  far_cbosprofissional.fa54_i_codigo = sau_triagemavulsa.s152_i_cbosprofissional";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_triagemavulsa.s152_i_cgsund";
     $sql .= "      inner join far_cbos  as a on   a.fa53_i_codigo = far_cbosprofissional.fa54_i_cbos";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = far_cbosprofissional.fa54_i_unidademedico";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm  on cgm.z01_numcgm =  medicos.sd03_i_cgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidademedicos.sd04_i_unidade";
     $sql2 = "";
     if($dbwhere==""){
       if($s152_i_codigo!=null ){
         $sql2 .= " where sau_triagemavulsa.s152_i_codigo = $s152_i_codigo "; 
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
  /*
   *  Query utilizada para verificar se o CGS possui triagem e já consultou
   */
  function sql_query_consulta ( $s152_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from sau_triagemavulsa ";
    $sql .= " left join sau_triagemavulsaprontuario   on sau_triagemavulsaprontuario.s155_i_triagemavulsa = sau_triagemavulsa.s152_i_codigo";
    $sql .= " left join prontuariosclassificacaorisco on prontuariosclassificacaorisco.sd101_prontuarios  = sau_triagemavulsaprontuario.s155_i_prontuario";
    $sql .= " left join prontproced                   on prontproced.sd29_i_prontuario                    = sau_triagemavulsaprontuario.s155_i_prontuario";
    $sql .= " left join prontuarios                   on prontuarios.sd24_i_codigo                        = prontproced.sd29_i_prontuario";
    $sql .= " left join prontagendamento              on prontagendamento.s102_i_prontuario               = prontuarios.sd24_i_codigo";
    $sql2 = "";
    if($dbwhere==""){
      if($s152_i_codigo!=null ){
        $sql2 .= " where sau_triagemavulsa.s152_i_codigo = $s152_i_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 .= " where $dbwhere";
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
