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

//MODULO: saude
//CLASSE DA ENTIDADE prontproced
class cl_tmp_prontproced { 
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
   var $sd29_i_codigo = 0; 
   var $sd29_i_prontuario = 0; 
   var $sd29_i_procedimento = 0; 
   var $sd29_d_data_dia = null; 
   var $sd29_d_data_mes = null; 
   var $sd29_d_data_ano = null; 
   var $sd29_d_data = null; 
   var $sd29_c_hora = null; 
   var $sd29_t_tratamento = null; 
   var $sd29_i_usuario = 0; 
   var $sd29_d_cadastro_dia = null; 
   var $sd29_d_cadastro_mes = null; 
   var $sd29_d_cadastro_ano = null; 
   var $sd29_d_cadastro = null; 
   var $sd29_c_cadastro = null; 
   var $sd29_i_profissional = 0; 
   var $sd29_t_diagnostico = null;
   // tabela temporaria
   var $tmp_prontproced = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd29_i_codigo = int4 = C�digo 
                 sd29_i_prontuario = int4 = Prontuario 
                 sd29_i_procedimento = int4 = Procedimento 
                 sd29_d_data = date = Data 
                 sd29_c_hora = char(5) = Hora 
                 sd29_t_tratamento = text = Tratamento 
                 sd29_i_usuario = int4 = Cod. Usu�rio 
                 sd29_d_cadastro = date = Data Cadastro 
                 sd29_c_cadastro = varchar(20) = Hora Cadastro 
                 sd29_i_profissional = int4 = Profissional / CBO 
                 sd29_t_diagnostico = text = Diagn�stico 
                 ";
   //funcao construtor da classe 
   function cl_tmp_prontproced() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontproced"); 
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
       $this->sd29_i_codigo = ($this->sd29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_i_codigo"]:$this->sd29_i_codigo);
       $this->sd29_i_prontuario = ($this->sd29_i_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_i_prontuario"]:$this->sd29_i_prontuario);
       $this->sd29_i_procedimento = ($this->sd29_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_i_procedimento"]:$this->sd29_i_procedimento);
       if($this->sd29_d_data == ""){
         $this->sd29_d_data_dia = ($this->sd29_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_d_data_dia"]:$this->sd29_d_data_dia);
         $this->sd29_d_data_mes = ($this->sd29_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_d_data_mes"]:$this->sd29_d_data_mes);
         $this->sd29_d_data_ano = ($this->sd29_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_d_data_ano"]:$this->sd29_d_data_ano);
         if($this->sd29_d_data_dia != ""){
            $this->sd29_d_data = $this->sd29_d_data_ano."-".$this->sd29_d_data_mes."-".$this->sd29_d_data_dia;
         }
       }
       $this->sd29_c_hora = ($this->sd29_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_c_hora"]:$this->sd29_c_hora);
       $this->sd29_t_tratamento = ($this->sd29_t_tratamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_t_tratamento"]:$this->sd29_t_tratamento);
       $this->sd29_i_usuario = ($this->sd29_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_i_usuario"]:$this->sd29_i_usuario);
       if($this->sd29_d_cadastro == ""){
         $this->sd29_d_cadastro_dia = ($this->sd29_d_cadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_d_cadastro_dia"]:$this->sd29_d_cadastro_dia);
         $this->sd29_d_cadastro_mes = ($this->sd29_d_cadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_d_cadastro_mes"]:$this->sd29_d_cadastro_mes);
         $this->sd29_d_cadastro_ano = ($this->sd29_d_cadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_d_cadastro_ano"]:$this->sd29_d_cadastro_ano);
         if($this->sd29_d_cadastro_dia != ""){
            $this->sd29_d_cadastro = $this->sd29_d_cadastro_ano."-".$this->sd29_d_cadastro_mes."-".$this->sd29_d_cadastro_dia;
         }
       }
       $this->sd29_c_cadastro = ($this->sd29_c_cadastro == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_c_cadastro"]:$this->sd29_c_cadastro);
       $this->sd29_i_profissional = ($this->sd29_i_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_i_profissional"]:$this->sd29_i_profissional);
       $this->sd29_t_diagnostico = ($this->sd29_t_diagnostico == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_t_diagnostico"]:$this->sd29_t_diagnostico);
     }else{
       $this->sd29_i_codigo = ($this->sd29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd29_i_codigo"]:$this->sd29_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd29_i_codigo){ 
      $this->atualizacampos();
     if($this->sd29_i_prontuario == null ){ 
       $this->erro_sql = " Campo Prontuario nao Informado.";
       $this->erro_campo = "sd29_i_prontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd29_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "sd29_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd29_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "sd29_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd29_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "sd29_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd29_i_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usu�rio nao Informado.";
       $this->erro_campo = "sd29_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd29_d_cadastro == null ){ 
       $this->sd29_d_cadastro = "now()";
     }
     if($this->sd29_c_cadastro == null ){ 
       $this->sd29_c_cadastro = "current_time";
     }
     if($this->sd29_i_profissional == null ){ 
       $this->erro_sql = " Campo Profissional / CBO nao Informado.";
       $this->erro_campo = "sd29_i_profissional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd29_i_codigo == "" || $sd29_i_codigo == null ){
       $result = db_query("select nextval('prontproced_sd29_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontproced_sd29_i_codigo_seq do campo: sd29_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd29_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontproced_sd29_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd29_i_codigo)){
         $this->erro_sql = " Campo sd29_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd29_i_codigo = $sd29_i_codigo; 
       }
     }
     if(($this->sd29_i_codigo == null) || ($this->sd29_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd29_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ".$this->tmp_prontproced."(
                                       sd29_i_codigo 
                                      ,sd29_i_prontuario 
                                      ,sd29_i_procedimento 
                                      ,sd29_d_data 
                                      ,sd29_c_hora 
                                      ,sd29_t_tratamento 
                                      ,sd29_i_usuario 
                                      ,sd29_d_cadastro 
                                      ,sd29_c_cadastro 
                                      ,sd29_i_profissional 
                                      ,sd29_t_diagnostico 
                       )
                values (
                                $this->sd29_i_codigo 
                               ,$this->sd29_i_prontuario 
                               ,$this->sd29_i_procedimento 
                               ,".($this->sd29_d_data == "null" || $this->sd29_d_data == ""?"null":"'".$this->sd29_d_data."'")." 
                               ,'$this->sd29_c_hora' 
                               ,'$this->sd29_t_tratamento' 
                               ,$this->sd29_i_usuario 
                               ,".($this->sd29_d_cadastro == "null" || $this->sd29_d_cadastro == ""?"null":"'".$this->sd29_d_cadastro."'")." 
                               ,'$this->sd29_c_cadastro' 
                               ,$this->sd29_i_profissional 
                               ,'$this->sd29_t_diagnostico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimentos do Prontuario ($this->sd29_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimentos do Prontuario j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimentos do Prontuario ($this->sd29_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd29_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd29_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1006283,'$this->sd29_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1006042,1006283,'','".AddSlashes(pg_result($resaco,0,'sd29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,1006282,'','".AddSlashes(pg_result($resaco,0,'sd29_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,100178,'','".AddSlashes(pg_result($resaco,0,'sd29_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,1008741,'','".AddSlashes(pg_result($resaco,0,'sd29_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,1008742,'','".AddSlashes(pg_result($resaco,0,'sd29_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,100188,'','".AddSlashes(pg_result($resaco,0,'sd29_t_tratamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,100183,'','".AddSlashes(pg_result($resaco,0,'sd29_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,1008912,'','".AddSlashes(pg_result($resaco,0,'sd29_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,1008913,'','".AddSlashes(pg_result($resaco,0,'sd29_c_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,100177,'','".AddSlashes(pg_result($resaco,0,'sd29_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006042,11883,'','".AddSlashes(pg_result($resaco,0,'sd29_t_diagnostico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd29_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update ".$this->tmp_prontproced." set ";
     $virgula = "";
     if(trim($this->sd29_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_codigo"])){ 
       $sql  .= $virgula." sd29_i_codigo = $this->sd29_i_codigo ";
       $virgula = ",";
       if(trim($this->sd29_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "sd29_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd29_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_prontuario"])){ 
       $sql  .= $virgula." sd29_i_prontuario = $this->sd29_i_prontuario ";
       $virgula = ",";
       if(trim($this->sd29_i_prontuario) == null ){ 
         $this->erro_sql = " Campo Prontuario nao Informado.";
         $this->erro_campo = "sd29_i_prontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd29_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_procedimento"])){ 
       $sql  .= $virgula." sd29_i_procedimento = $this->sd29_i_procedimento ";
       $virgula = ",";
       if(trim($this->sd29_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "sd29_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd29_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd29_d_data_dia"] !="") ){ 
       $sql  .= $virgula." sd29_d_data = '$this->sd29_d_data' ";
       $virgula = ",";
       if(trim($this->sd29_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "sd29_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_d_data_dia"])){ 
         $sql  .= $virgula." sd29_d_data = null ";
         $virgula = ",";
         if(trim($this->sd29_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "sd29_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd29_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_c_hora"])){ 
       $sql  .= $virgula." sd29_c_hora = '$this->sd29_c_hora' ";
       $virgula = ",";
       if(trim($this->sd29_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "sd29_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd29_t_tratamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_t_tratamento"])){ 
       $sql  .= $virgula." sd29_t_tratamento = '$this->sd29_t_tratamento' ";
       $virgula = ",";
     }
     if(trim($this->sd29_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_usuario"])){ 
       $sql  .= $virgula." sd29_i_usuario = $this->sd29_i_usuario ";
       $virgula = ",";
       if(trim($this->sd29_i_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usu�rio nao Informado.";
         $this->erro_campo = "sd29_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd29_d_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_d_cadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd29_d_cadastro_dia"] !="") ){ 
       $sql  .= $virgula." sd29_d_cadastro = '$this->sd29_d_cadastro' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_d_cadastro_dia"])){ 
         $sql  .= $virgula." sd29_d_cadastro = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd29_c_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_c_cadastro"])){ 
       $sql  .= $virgula." sd29_c_cadastro = '$this->sd29_c_cadastro' ";
       $virgula = ",";
     }
     if(trim($this->sd29_i_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_profissional"])){ 
       $sql  .= $virgula." sd29_i_profissional = $this->sd29_i_profissional ";
       $virgula = ",";
       if(trim($this->sd29_i_profissional) == null ){ 
         $this->erro_sql = " Campo Profissional / CBO nao Informado.";
         $this->erro_campo = "sd29_i_profissional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd29_t_diagnostico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd29_t_diagnostico"])){ 
       $sql  .= $virgula." sd29_t_diagnostico = '$this->sd29_t_diagnostico' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd29_i_codigo!=null){
       $sql .= " sd29_i_codigo = $this->sd29_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd29_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1006283,'$this->sd29_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1006042,1006283,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_i_codigo'))."','$this->sd29_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_prontuario"]))
           $resac = db_query("insert into db_acount values($acount,1006042,1006282,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_i_prontuario'))."','$this->sd29_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_procedimento"]))
           $resac = db_query("insert into db_acount values($acount,1006042,100178,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_i_procedimento'))."','$this->sd29_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1006042,1008741,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_d_data'))."','$this->sd29_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_c_hora"]))
           $resac = db_query("insert into db_acount values($acount,1006042,1008742,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_c_hora'))."','$this->sd29_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_t_tratamento"]))
           $resac = db_query("insert into db_acount values($acount,1006042,100188,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_t_tratamento'))."','$this->sd29_t_tratamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1006042,100183,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_i_usuario'))."','$this->sd29_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_d_cadastro"]))
           $resac = db_query("insert into db_acount values($acount,1006042,1008912,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_d_cadastro'))."','$this->sd29_d_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_c_cadastro"]))
           $resac = db_query("insert into db_acount values($acount,1006042,1008913,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_c_cadastro'))."','$this->sd29_c_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_i_profissional"]))
           $resac = db_query("insert into db_acount values($acount,1006042,100177,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_i_profissional'))."','$this->sd29_i_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd29_t_diagnostico"]))
           $resac = db_query("insert into db_acount values($acount,1006042,11883,'".AddSlashes(pg_result($resaco,$conresaco,'sd29_t_diagnostico'))."','$this->sd29_t_diagnostico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos do Prontuario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd29_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos do Prontuario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd29_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd29_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1006283,'$sd29_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1006042,1006283,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,1006282,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,100178,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,1008741,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,1008742,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,100188,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_t_tratamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,100183,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,1008912,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,1008913,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_c_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,100177,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006042,11883,'','".AddSlashes(pg_result($resaco,$iresaco,'sd29_t_diagnostico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ".$this->tmp_prontproced."
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd29_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd29_i_codigo = $sd29_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos do Prontuario nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd29_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos do Prontuario nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na ".$this->tmp_prontproced."";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ".$this->tmp_prontproced." ";
     $sql .= "      left join prontuarios  on  prontuarios.sd24_i_codigo = ".$this->tmp_prontproced.".sd29_i_prontuario";
     $sql .= "      left join especmedico  on  especmedico.sd27_i_codigo = ".$this->tmp_prontproced.".sd29_i_profissional";
     $sql .= "      left join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      left join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";

     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm m on  m.z01_numcgm = medicos.sd03_i_cgm";

     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ".$this->tmp_prontproced.".sd29_i_usuario";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = ".$this->tmp_prontproced.".sd29_i_procedimento";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql .= "      left join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = unidademedicos.sd04_i_orgaoemissor";
     $sql .= "      left join sau_modvinculo  on  sau_modvinculo.sd52_i_vinculacao = unidademedicos.sd04_i_vinculo";
     $sql .= "      left join sau_cid  on  sau_cid.sd70_i_codigo = prontuarios.sd24_i_cid";
     $sql .= "      left join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      left join cgs_und on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($sd29_i_codigo!=null ){
         $sql2 .= " where ".$this->tmp_prontproced.".sd29_i_codigo = $sd29_i_codigo "; 
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
   function sql_query_file ( $sd29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ".$this->tmp_prontproced." ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd29_i_codigo!=null ){
         $sql2 .= " where ".$this->tmp_prontproced.".sd29_i_codigo = $sd29_i_codigo "; 
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