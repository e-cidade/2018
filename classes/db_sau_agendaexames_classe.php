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
//CLASSE DA ENTIDADE sau_agendaexames
class cl_sau_agendaexames { 
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
   var $s113_i_codigo = 0; 
   var $s113_i_prestadorhorarios = 0; 
   var $s113_i_numcgs = 0; 
   var $s113_d_agendamento_dia = null; 
   var $s113_d_agendamento_mes = null; 
   var $s113_d_agendamento_ano = null; 
   var $s113_d_agendamento = null; 
   var $s113_d_exame_dia = null; 
   var $s113_d_exame_mes = null; 
   var $s113_d_exame_ano = null; 
   var $s113_d_exame = null; 
   var $s113_i_ficha = 0; 
   var $s113_c_hora = null; 
   var $s113_i_situacao = 0; 
   var $s113_d_cadastro_dia = null; 
   var $s113_d_cadastro_mes = null; 
   var $s113_d_cadastro_ano = null; 
   var $s113_d_cadastro = null; 
   var $s113_c_cadastro = null; 
   var $s113_i_login = 0; 
   var $s113_c_encaminhamento = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s113_i_codigo = int4 = Código 
                 s113_i_prestadorhorarios = int4 = Prestador Horários 
                 s113_i_numcgs = int4 = CGS 
                 s113_d_agendamento = date = Agendamento 
                 s113_d_exame = date = Agenda 
                 s113_i_ficha = int4 = Ficha 
                 s113_c_hora = char(5) = Hora 
                 s113_i_situacao = int4 = Situação 
                 s113_d_cadastro = date = Cadastro 
                 s113_c_cadastro = char(20) = Hora Cadastro 
                 s113_i_login = int4 = Login 
                 s113_c_encaminhamento = varchar(10) = Encaminhamento 
                 ";
   //funcao construtor da classe 
   function cl_sau_agendaexames() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_agendaexames"); 
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
       $this->s113_i_codigo = ($this->s113_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_i_codigo"]:$this->s113_i_codigo);
       $this->s113_i_prestadorhorarios = ($this->s113_i_prestadorhorarios == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_i_prestadorhorarios"]:$this->s113_i_prestadorhorarios);
       $this->s113_i_numcgs = ($this->s113_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_i_numcgs"]:$this->s113_i_numcgs);
       if($this->s113_d_agendamento == ""){
         $this->s113_d_agendamento_dia = ($this->s113_d_agendamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_agendamento_dia"]:$this->s113_d_agendamento_dia);
         $this->s113_d_agendamento_mes = ($this->s113_d_agendamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_agendamento_mes"]:$this->s113_d_agendamento_mes);
         $this->s113_d_agendamento_ano = ($this->s113_d_agendamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_agendamento_ano"]:$this->s113_d_agendamento_ano);
         if($this->s113_d_agendamento_dia != ""){
            $this->s113_d_agendamento = $this->s113_d_agendamento_ano."-".$this->s113_d_agendamento_mes."-".$this->s113_d_agendamento_dia;
         }
       }
       if($this->s113_d_exame == ""){
         $this->s113_d_exame_dia = ($this->s113_d_exame_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_exame_dia"]:$this->s113_d_exame_dia);
         $this->s113_d_exame_mes = ($this->s113_d_exame_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_exame_mes"]:$this->s113_d_exame_mes);
         $this->s113_d_exame_ano = ($this->s113_d_exame_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_exame_ano"]:$this->s113_d_exame_ano);
         if($this->s113_d_exame_dia != ""){
            $this->s113_d_exame = $this->s113_d_exame_ano."-".$this->s113_d_exame_mes."-".$this->s113_d_exame_dia;
         }
       }
       $this->s113_i_ficha = ($this->s113_i_ficha == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_i_ficha"]:$this->s113_i_ficha);
       $this->s113_c_hora = ($this->s113_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_c_hora"]:$this->s113_c_hora);
       $this->s113_i_situacao = ($this->s113_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_i_situacao"]:$this->s113_i_situacao);
       if($this->s113_d_cadastro == ""){
         $this->s113_d_cadastro_dia = ($this->s113_d_cadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_cadastro_dia"]:$this->s113_d_cadastro_dia);
         $this->s113_d_cadastro_mes = ($this->s113_d_cadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_cadastro_mes"]:$this->s113_d_cadastro_mes);
         $this->s113_d_cadastro_ano = ($this->s113_d_cadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_d_cadastro_ano"]:$this->s113_d_cadastro_ano);
         if($this->s113_d_cadastro_dia != ""){
            $this->s113_d_cadastro = $this->s113_d_cadastro_ano."-".$this->s113_d_cadastro_mes."-".$this->s113_d_cadastro_dia;
         }
       }
       $this->s113_c_cadastro = ($this->s113_c_cadastro == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_c_cadastro"]:$this->s113_c_cadastro);
       $this->s113_i_login = ($this->s113_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_i_login"]:$this->s113_i_login);
       $this->s113_c_encaminhamento = ($this->s113_c_encaminhamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_c_encaminhamento"]:$this->s113_c_encaminhamento);
     }else{
       $this->s113_i_codigo = ($this->s113_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s113_i_codigo"]:$this->s113_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s113_i_codigo){ 
      $this->atualizacampos();
     if($this->s113_i_prestadorhorarios == null ){ 
       $this->erro_sql = " Campo Prestador Horários nao Informado.";
       $this->erro_campo = "s113_i_prestadorhorarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s113_i_numcgs == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "s113_i_numcgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s113_d_agendamento == null ){ 
       $this->erro_sql = " Campo Agendamento nao Informado.";
       $this->erro_campo = "s113_d_agendamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s113_d_exame == null ){ 
       $this->erro_sql = " Campo Agenda nao Informado.";
       $this->erro_campo = "s113_d_exame_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s113_i_ficha == null ){ 
       $this->erro_sql = " Campo Ficha nao Informado.";
       $this->erro_campo = "s113_i_ficha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s113_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "s113_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s113_i_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "s113_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s113_d_cadastro == null ){ 
       $this->s113_d_cadastro = "now()";
     }
     if($this->s113_c_cadastro == null ){ 
       $this->s113_c_cadastro = "'||current_time||'";
     }
     if($this->s113_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "s113_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s113_c_encaminhamento == null ){ 
       $this->erro_sql = " Campo Encaminhamento nao Informado.";
       $this->erro_campo = "s113_c_encaminhamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s113_i_codigo == "" || $s113_i_codigo == null ){
       $result = db_query("select nextval('sau_agendaexames_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_agendaexames_codigo_seq do campo: s113_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s113_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_agendaexames_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s113_i_codigo)){
         $this->erro_sql = " Campo s113_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s113_i_codigo = $s113_i_codigo; 
       }
     }
     if(($this->s113_i_codigo == null) || ($this->s113_i_codigo == "") ){ 
       $this->erro_sql = " Campo s113_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_agendaexames(
                                       s113_i_codigo 
                                      ,s113_i_prestadorhorarios 
                                      ,s113_i_numcgs 
                                      ,s113_d_agendamento 
                                      ,s113_d_exame 
                                      ,s113_i_ficha 
                                      ,s113_c_hora 
                                      ,s113_i_situacao 
                                      ,s113_d_cadastro 
                                      ,s113_c_cadastro 
                                      ,s113_i_login 
                                      ,s113_c_encaminhamento 
                       )
                values (
                                $this->s113_i_codigo 
                               ,$this->s113_i_prestadorhorarios 
                               ,$this->s113_i_numcgs 
                               ,".($this->s113_d_agendamento == "null" || $this->s113_d_agendamento == ""?"null":"'".$this->s113_d_agendamento."'")." 
                               ,".($this->s113_d_exame == "null" || $this->s113_d_exame == ""?"null":"'".$this->s113_d_exame."'")." 
                               ,$this->s113_i_ficha 
                               ,'$this->s113_c_hora' 
                               ,$this->s113_i_situacao 
                               ,".($this->s113_d_cadastro == "null" || $this->s113_d_cadastro == ""?"null":"'".$this->s113_d_cadastro."'")." 
                               ,'$this->s113_c_cadastro' 
                               ,$this->s113_i_login 
                               ,'$this->s113_c_encaminhamento' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agenda Exames ($this->s113_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agenda Exames já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agenda Exames ($this->s113_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s113_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s113_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13587,'$this->s113_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2380,13587,'','".AddSlashes(pg_result($resaco,0,'s113_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13588,'','".AddSlashes(pg_result($resaco,0,'s113_i_prestadorhorarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13589,'','".AddSlashes(pg_result($resaco,0,'s113_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13590,'','".AddSlashes(pg_result($resaco,0,'s113_d_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13591,'','".AddSlashes(pg_result($resaco,0,'s113_d_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13592,'','".AddSlashes(pg_result($resaco,0,'s113_i_ficha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13599,'','".AddSlashes(pg_result($resaco,0,'s113_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13600,'','".AddSlashes(pg_result($resaco,0,'s113_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13601,'','".AddSlashes(pg_result($resaco,0,'s113_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13602,'','".AddSlashes(pg_result($resaco,0,'s113_c_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,13603,'','".AddSlashes(pg_result($resaco,0,'s113_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2380,14323,'','".AddSlashes(pg_result($resaco,0,'s113_c_encaminhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s113_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_agendaexames set ";
     $virgula = "";
     if(trim($this->s113_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_i_codigo"])){ 
       $sql  .= $virgula." s113_i_codigo = $this->s113_i_codigo ";
       $virgula = ",";
       if(trim($this->s113_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s113_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s113_i_prestadorhorarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_i_prestadorhorarios"])){ 
       $sql  .= $virgula." s113_i_prestadorhorarios = $this->s113_i_prestadorhorarios ";
       $virgula = ",";
       if(trim($this->s113_i_prestadorhorarios) == null ){ 
         $this->erro_sql = " Campo Prestador Horários nao Informado.";
         $this->erro_campo = "s113_i_prestadorhorarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s113_i_numcgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_i_numcgs"])){ 
       $sql  .= $virgula." s113_i_numcgs = $this->s113_i_numcgs ";
       $virgula = ",";
       if(trim($this->s113_i_numcgs) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "s113_i_numcgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s113_d_agendamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_d_agendamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s113_d_agendamento_dia"] !="") ){ 
       $sql  .= $virgula." s113_d_agendamento = '$this->s113_d_agendamento' ";
       $virgula = ",";
       if(trim($this->s113_d_agendamento) == null ){ 
         $this->erro_sql = " Campo Agendamento nao Informado.";
         $this->erro_campo = "s113_d_agendamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s113_d_agendamento_dia"])){ 
         $sql  .= $virgula." s113_d_agendamento = null ";
         $virgula = ",";
         if(trim($this->s113_d_agendamento) == null ){ 
           $this->erro_sql = " Campo Agendamento nao Informado.";
           $this->erro_campo = "s113_d_agendamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s113_d_exame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_d_exame_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s113_d_exame_dia"] !="") ){ 
       $sql  .= $virgula." s113_d_exame = '$this->s113_d_exame' ";
       $virgula = ",";
       if(trim($this->s113_d_exame) == null ){ 
         $this->erro_sql = " Campo Agenda nao Informado.";
         $this->erro_campo = "s113_d_exame_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s113_d_exame_dia"])){ 
         $sql  .= $virgula." s113_d_exame = null ";
         $virgula = ",";
         if(trim($this->s113_d_exame) == null ){ 
           $this->erro_sql = " Campo Agenda nao Informado.";
           $this->erro_campo = "s113_d_exame_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s113_i_ficha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_i_ficha"])){ 
       $sql  .= $virgula." s113_i_ficha = $this->s113_i_ficha ";
       $virgula = ",";
       if(trim($this->s113_i_ficha) == null ){ 
         $this->erro_sql = " Campo Ficha nao Informado.";
         $this->erro_campo = "s113_i_ficha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s113_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_c_hora"])){ 
       $sql  .= $virgula." s113_c_hora = '$this->s113_c_hora' ";
       $virgula = ",";
       if(trim($this->s113_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "s113_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s113_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_i_situacao"])){ 
       $sql  .= $virgula." s113_i_situacao = $this->s113_i_situacao ";
       $virgula = ",";
       if(trim($this->s113_i_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "s113_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s113_d_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_d_cadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s113_d_cadastro_dia"] !="") ){ 
       $sql  .= $virgula." s113_d_cadastro = '$this->s113_d_cadastro' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s113_d_cadastro_dia"])){ 
         $sql  .= $virgula." s113_d_cadastro = null ";
         $virgula = ",";
       }
     }
     if(trim($this->s113_c_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_c_cadastro"])){ 
       $sql  .= $virgula." s113_c_cadastro = '$this->s113_c_cadastro' ";
       $virgula = ",";
     }
     if(trim($this->s113_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_i_login"])){ 
       $sql  .= $virgula." s113_i_login = $this->s113_i_login ";
       $virgula = ",";
       if(trim($this->s113_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "s113_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s113_c_encaminhamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s113_c_encaminhamento"])){ 
       $sql  .= $virgula." s113_c_encaminhamento = '$this->s113_c_encaminhamento' ";
       $virgula = ",";
       if(trim($this->s113_c_encaminhamento) == null ){ 
         $this->erro_sql = " Campo Encaminhamento nao Informado.";
         $this->erro_campo = "s113_c_encaminhamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s113_i_codigo!=null){
       $sql .= " s113_i_codigo = $this->s113_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s113_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13587,'$this->s113_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_i_codigo"]) || $this->s113_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2380,13587,'".AddSlashes(pg_result($resaco,$conresaco,'s113_i_codigo'))."','$this->s113_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_i_prestadorhorarios"]) || $this->s113_i_prestadorhorarios != "")
           $resac = db_query("insert into db_acount values($acount,2380,13588,'".AddSlashes(pg_result($resaco,$conresaco,'s113_i_prestadorhorarios'))."','$this->s113_i_prestadorhorarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_i_numcgs"]) || $this->s113_i_numcgs != "")
           $resac = db_query("insert into db_acount values($acount,2380,13589,'".AddSlashes(pg_result($resaco,$conresaco,'s113_i_numcgs'))."','$this->s113_i_numcgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_d_agendamento"]) || $this->s113_d_agendamento != "")
           $resac = db_query("insert into db_acount values($acount,2380,13590,'".AddSlashes(pg_result($resaco,$conresaco,'s113_d_agendamento'))."','$this->s113_d_agendamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_d_exame"]) || $this->s113_d_exame != "")
           $resac = db_query("insert into db_acount values($acount,2380,13591,'".AddSlashes(pg_result($resaco,$conresaco,'s113_d_exame'))."','$this->s113_d_exame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_i_ficha"]) || $this->s113_i_ficha != "")
           $resac = db_query("insert into db_acount values($acount,2380,13592,'".AddSlashes(pg_result($resaco,$conresaco,'s113_i_ficha'))."','$this->s113_i_ficha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_c_hora"]) || $this->s113_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2380,13599,'".AddSlashes(pg_result($resaco,$conresaco,'s113_c_hora'))."','$this->s113_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_i_situacao"]) || $this->s113_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2380,13600,'".AddSlashes(pg_result($resaco,$conresaco,'s113_i_situacao'))."','$this->s113_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_d_cadastro"]) || $this->s113_d_cadastro != "")
           $resac = db_query("insert into db_acount values($acount,2380,13601,'".AddSlashes(pg_result($resaco,$conresaco,'s113_d_cadastro'))."','$this->s113_d_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_c_cadastro"]) || $this->s113_c_cadastro != "")
           $resac = db_query("insert into db_acount values($acount,2380,13602,'".AddSlashes(pg_result($resaco,$conresaco,'s113_c_cadastro'))."','$this->s113_c_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_i_login"]) || $this->s113_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2380,13603,'".AddSlashes(pg_result($resaco,$conresaco,'s113_i_login'))."','$this->s113_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s113_c_encaminhamento"]) || $this->s113_c_encaminhamento != "")
           $resac = db_query("insert into db_acount values($acount,2380,14323,'".AddSlashes(pg_result($resaco,$conresaco,'s113_c_encaminhamento'))."','$this->s113_c_encaminhamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda Exames nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s113_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agenda Exames nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s113_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s113_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s113_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s113_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13587,'$s113_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2380,13587,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13588,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_i_prestadorhorarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13589,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13590,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_d_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13591,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_d_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13592,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_i_ficha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13599,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13600,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13601,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13602,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_c_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,13603,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2380,14323,'','".AddSlashes(pg_result($resaco,$iresaco,'s113_c_encaminhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_agendaexames
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s113_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s113_i_codigo = $s113_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda Exames nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s113_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agenda Exames nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s113_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s113_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_agendaexames";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s113_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_agendaexames ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_agendaexames.s113_i_login";
     $sql .= "      inner join sau_prestadorhorarios  on  sau_prestadorhorarios.s112_i_codigo = sau_agendaexames.s113_i_prestadorhorarios";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_agendaexames.s113_i_numcgs";
     $sql .= "      inner join sau_tipoficha  on  sau_tipoficha.sd101_i_codigo = sau_prestadorhorarios.s112_i_tipoficha";
     $sql .= "      inner join sau_prestadorvinculos  on  sau_prestadorvinculos.s111_i_codigo = sau_prestadorhorarios.s112_i_prestadorvinc";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = sau_prestadorhorarios.s112_i_diasemana";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as a on   a.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($s113_i_codigo!=null ){
         $sql2 .= " where sau_agendaexames.s113_i_codigo = $s113_i_codigo "; 
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
   function sql_query_file ( $s113_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_agendaexames ";
     $sql2 = "";
     if($dbwhere==""){
       if($s113_i_codigo!=null ){
         $sql2 .= " where sau_agendaexames.s113_i_codigo = $s113_i_codigo "; 
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