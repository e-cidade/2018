<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE agendaconsultaanula
class cl_agendaconsultaanula { 
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
   var $s114_i_codigo = 0; 
   var $s114_i_agendaconsulta = 0; 
   var $s114_d_data_dia = null; 
   var $s114_d_data_mes = null; 
   var $s114_d_data_ano = null; 
   var $s114_d_data = null; 
   var $s114_v_motivo = null; 
   var $s114_i_situacao = 0; 
   var $s114_i_login = 0; 
   var $s114_c_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s114_i_codigo = int4 = Código 
                 s114_i_agendaconsulta = int4 = Agenda Consulta 
                 s114_d_data = date = Data Anulação 
                 s114_v_motivo = varchar(100) = Motivo 
                 s114_i_situacao = int4 = Situação 
                 s114_i_login = int4 = Login 
                 s114_c_hora = varchar(5) = s114_c_hora 
                 ";
   //funcao construtor da classe 
   function cl_agendaconsultaanula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agendaconsultaanula"); 
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
       $this->s114_i_codigo = ($this->s114_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_i_codigo"]:$this->s114_i_codigo);
       $this->s114_i_agendaconsulta = ($this->s114_i_agendaconsulta == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_i_agendaconsulta"]:$this->s114_i_agendaconsulta);
       if($this->s114_d_data == ""){
         $this->s114_d_data_dia = ($this->s114_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_d_data_dia"]:$this->s114_d_data_dia);
         $this->s114_d_data_mes = ($this->s114_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_d_data_mes"]:$this->s114_d_data_mes);
         $this->s114_d_data_ano = ($this->s114_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_d_data_ano"]:$this->s114_d_data_ano);
         if($this->s114_d_data_dia != ""){
            $this->s114_d_data = $this->s114_d_data_ano."-".$this->s114_d_data_mes."-".$this->s114_d_data_dia;
         }
       }
       $this->s114_v_motivo = ($this->s114_v_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_v_motivo"]:$this->s114_v_motivo);
       $this->s114_i_situacao = ($this->s114_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_i_situacao"]:$this->s114_i_situacao);
       $this->s114_i_login = ($this->s114_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_i_login"]:$this->s114_i_login);
       $this->s114_c_hora = ($this->s114_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_c_hora"]:$this->s114_c_hora);
     }else{
       $this->s114_i_codigo = ($this->s114_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s114_i_codigo"]:$this->s114_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s114_i_codigo){ 
      $this->atualizacampos();
     if($this->s114_i_agendaconsulta == null ){ 
       $this->erro_sql = " Campo Agenda Consulta nao Informado.";
       $this->erro_campo = "s114_i_agendaconsulta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s114_d_data == null ){ 
       $this->s114_d_data = "now()";
     }
     if($this->s114_v_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "s114_v_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s114_i_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "s114_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s114_i_login == null ){ 
       $this->s114_i_login = "null";
     }
     if($this->s114_c_hora == null ){ 
       $this->erro_sql = " Campo s114_c_hora nao Informado.";
       $this->erro_campo = "s114_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s114_i_codigo == "" || $s114_i_codigo == null ){
       $result = db_query("select nextval('agendaconsultaanula_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agendaconsultaanula_codigo_seq do campo: s114_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s114_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agendaconsultaanula_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s114_i_codigo)){
         $this->erro_sql = " Campo s114_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s114_i_codigo = $s114_i_codigo; 
       }
     }
     if(($this->s114_i_codigo == null) || ($this->s114_i_codigo == "") ){ 
       $this->erro_sql = " Campo s114_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agendaconsultaanula(
                                       s114_i_codigo 
                                      ,s114_i_agendaconsulta 
                                      ,s114_d_data 
                                      ,s114_v_motivo 
                                      ,s114_i_situacao 
                                      ,s114_i_login 
                                      ,s114_c_hora 
                       )
                values (
                                $this->s114_i_codigo 
                               ,$this->s114_i_agendaconsulta 
                               ,".($this->s114_d_data == "null" || $this->s114_d_data == ""?"null":"'".$this->s114_d_data."'")." 
                               ,'$this->s114_v_motivo' 
                               ,$this->s114_i_situacao 
                               ,$this->s114_i_login 
                               ,'$this->s114_c_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Anula Agenda Consulta ($this->s114_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Anula Agenda Consulta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Anula Agenda Consulta ($this->s114_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s114_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s114_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13762,'$this->s114_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2410,13762,'','".AddSlashes(pg_result($resaco,0,'s114_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2410,13763,'','".AddSlashes(pg_result($resaco,0,'s114_i_agendaconsulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2410,13764,'','".AddSlashes(pg_result($resaco,0,'s114_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2410,13765,'','".AddSlashes(pg_result($resaco,0,'s114_v_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2410,13766,'','".AddSlashes(pg_result($resaco,0,'s114_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2410,13767,'','".AddSlashes(pg_result($resaco,0,'s114_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2410,15657,'','".AddSlashes(pg_result($resaco,0,'s114_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s114_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update agendaconsultaanula set ";
     $virgula = "";
     if(trim($this->s114_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s114_i_codigo"])){ 
       $sql  .= $virgula." s114_i_codigo = $this->s114_i_codigo ";
       $virgula = ",";
       if(trim($this->s114_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s114_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s114_i_agendaconsulta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s114_i_agendaconsulta"])){ 
       $sql  .= $virgula." s114_i_agendaconsulta = $this->s114_i_agendaconsulta ";
       $virgula = ",";
       if(trim($this->s114_i_agendaconsulta) == null ){ 
         $this->erro_sql = " Campo Agenda Consulta nao Informado.";
         $this->erro_campo = "s114_i_agendaconsulta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s114_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s114_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s114_d_data_dia"] !="") ){ 
       $sql  .= $virgula." s114_d_data = '$this->s114_d_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s114_d_data_dia"])){ 
         $sql  .= $virgula." s114_d_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->s114_v_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s114_v_motivo"])){ 
       $sql  .= $virgula." s114_v_motivo = '$this->s114_v_motivo' ";
       $virgula = ",";
       if(trim($this->s114_v_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "s114_v_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s114_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s114_i_situacao"])){ 
       $sql  .= $virgula." s114_i_situacao = $this->s114_i_situacao ";
       $virgula = ",";
       if(trim($this->s114_i_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "s114_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s114_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s114_i_login"])){ 
        if(trim($this->s114_i_login)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s114_i_login"])){ 
           $this->s114_i_login = "0" ; 
        } 
       $sql  .= $virgula." s114_i_login = $this->s114_i_login ";
       $virgula = ",";
     }
     if(trim($this->s114_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s114_c_hora"])){ 
       $sql  .= $virgula." s114_c_hora = '$this->s114_c_hora' ";
       $virgula = ",";
       if(trim($this->s114_c_hora) == null ){ 
         $this->erro_sql = " Campo s114_c_hora nao Informado.";
         $this->erro_campo = "s114_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s114_i_codigo!=null){
       $sql .= " s114_i_codigo = $this->s114_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s114_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13762,'$this->s114_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s114_i_codigo"]) || $this->s114_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2410,13762,'".AddSlashes(pg_result($resaco,$conresaco,'s114_i_codigo'))."','$this->s114_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s114_i_agendaconsulta"]) || $this->s114_i_agendaconsulta != "")
           $resac = db_query("insert into db_acount values($acount,2410,13763,'".AddSlashes(pg_result($resaco,$conresaco,'s114_i_agendaconsulta'))."','$this->s114_i_agendaconsulta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s114_d_data"]) || $this->s114_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2410,13764,'".AddSlashes(pg_result($resaco,$conresaco,'s114_d_data'))."','$this->s114_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s114_v_motivo"]) || $this->s114_v_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2410,13765,'".AddSlashes(pg_result($resaco,$conresaco,'s114_v_motivo'))."','$this->s114_v_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s114_i_situacao"]) || $this->s114_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2410,13766,'".AddSlashes(pg_result($resaco,$conresaco,'s114_i_situacao'))."','$this->s114_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s114_i_login"]) || $this->s114_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2410,13767,'".AddSlashes(pg_result($resaco,$conresaco,'s114_i_login'))."','$this->s114_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s114_c_hora"]) || $this->s114_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2410,15657,'".AddSlashes(pg_result($resaco,$conresaco,'s114_c_hora'))."','$this->s114_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anula Agenda Consulta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s114_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anula Agenda Consulta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s114_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s114_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s114_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s114_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13762,'$s114_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2410,13762,'','".AddSlashes(pg_result($resaco,$iresaco,'s114_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2410,13763,'','".AddSlashes(pg_result($resaco,$iresaco,'s114_i_agendaconsulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2410,13764,'','".AddSlashes(pg_result($resaco,$iresaco,'s114_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2410,13765,'','".AddSlashes(pg_result($resaco,$iresaco,'s114_v_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2410,13766,'','".AddSlashes(pg_result($resaco,$iresaco,'s114_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2410,13767,'','".AddSlashes(pg_result($resaco,$iresaco,'s114_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2410,15657,'','".AddSlashes(pg_result($resaco,$iresaco,'s114_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agendaconsultaanula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s114_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s114_i_codigo = $s114_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anula Agenda Consulta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s114_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anula Agenda Consulta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s114_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s114_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:agendaconsultaanula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s114_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendaconsultaanula ";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = agendaconsultaanula.s114_i_login";
     $sql .= "      inner join agendamentos  on  agendamentos.sd23_i_codigo = agendaconsultaanula.s114_i_agendaconsulta";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
     $sql .= "      inner join undmedhorario  on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($s114_i_codigo!=null ){
         $sql2 .= " where agendaconsultaanula.s114_i_codigo = $s114_i_codigo "; 
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
   function sql_query_file ( $s114_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendaconsultaanula ";
     $sql2 = "";
     if($dbwhere==""){
       if($s114_i_codigo!=null ){
         $sql2 .= " where agendaconsultaanula.s114_i_codigo = $s114_i_codigo "; 
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

   function sql_query_anulados ( $s114_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendaconsultaanula ";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = agendaconsultaanula.s114_i_login";
     $sql .= "      inner join agendamentos  on  agendamentos.sd23_i_codigo = agendaconsultaanula.s114_i_agendaconsulta";
     $sql .= "      inner join undmedhorario  on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor";
     $sql .= "      inner join sau_tipoficha  on sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha";
     $sql .= "      left  join prontagendamento  on  prontagendamento.s102_i_agendamento = agendamentos.sd23_i_codigo";
     $sql .= "      left  join prontuarios on prontuarios.sd24_i_codigo =  prontagendamento.s102_i_prontuario";
     $sql .= "      inner join especmedico  on especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = agendamentos.sd23_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($s114_i_codigo!=null ){
         $sql2 .= " where agendaconsultaanula.s114_i_codigo = $s114_i_codigo "; 
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