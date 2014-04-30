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
//CLASSE DA ENTIDADE prontuariomedico
class cl_prontuariomedico { 
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
   var $sd32_i_codigo = 0; 
   var $sd32_i_unidade = 0; 
   var $sd32_i_numcgs = 0; 
   var $sd32_i_medico = 0; 
   var $sd32_d_atendimento_dia = null; 
   var $sd32_d_atendimento_mes = null; 
   var $sd32_d_atendimento_ano = null; 
   var $sd32_d_atendimento = null; 
   var $sd32_c_horaatend = null; 
   var $sd32_t_descricao = null; 
   var $sd32_d_datacad_dia = null; 
   var $sd32_d_datacad_mes = null; 
   var $sd32_d_datacad_ano = null; 
   var $sd32_d_datacad = null; 
   var $sd32_c_horacad = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd32_i_codigo = int4 = Código 
                 sd32_i_unidade = int4 = Unidade 
                 sd32_i_numcgs = int4 = CGS 
                 sd32_i_medico = int4 = Médico 
                 sd32_d_atendimento = date = Data Atendimento 
                 sd32_c_horaatend = varchar(5) = Hora Atendimento 
                 sd32_t_descricao = text = Descrição 
                 sd32_d_datacad = date = Data Cadastro 
                 sd32_c_horacad = varchar(20) = Hora Cadastro 
                 ";
   //funcao construtor da classe 
   function cl_prontuariomedico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontuariomedico"); 
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
       $this->sd32_i_codigo = ($this->sd32_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_i_codigo"]:$this->sd32_i_codigo);
       $this->sd32_i_unidade = ($this->sd32_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_i_unidade"]:$this->sd32_i_unidade);
       $this->sd32_i_numcgs = ($this->sd32_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_i_numcgs"]:$this->sd32_i_numcgs);
       $this->sd32_i_medico = ($this->sd32_i_medico == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_i_medico"]:$this->sd32_i_medico);
       if($this->sd32_d_atendimento == ""){
         $this->sd32_d_atendimento_dia = ($this->sd32_d_atendimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_d_atendimento_dia"]:$this->sd32_d_atendimento_dia);
         $this->sd32_d_atendimento_mes = ($this->sd32_d_atendimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_d_atendimento_mes"]:$this->sd32_d_atendimento_mes);
         $this->sd32_d_atendimento_ano = ($this->sd32_d_atendimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_d_atendimento_ano"]:$this->sd32_d_atendimento_ano);
         if($this->sd32_d_atendimento_dia != ""){
            $this->sd32_d_atendimento = $this->sd32_d_atendimento_ano."-".$this->sd32_d_atendimento_mes."-".$this->sd32_d_atendimento_dia;
         }
       }
       $this->sd32_c_horaatend = ($this->sd32_c_horaatend == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_c_horaatend"]:$this->sd32_c_horaatend);
       $this->sd32_t_descricao = ($this->sd32_t_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_t_descricao"]:$this->sd32_t_descricao);
       if($this->sd32_d_datacad == ""){
         $this->sd32_d_datacad_dia = ($this->sd32_d_datacad_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_d_datacad_dia"]:$this->sd32_d_datacad_dia);
         $this->sd32_d_datacad_mes = ($this->sd32_d_datacad_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_d_datacad_mes"]:$this->sd32_d_datacad_mes);
         $this->sd32_d_datacad_ano = ($this->sd32_d_datacad_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_d_datacad_ano"]:$this->sd32_d_datacad_ano);
         if($this->sd32_d_datacad_dia != ""){
            $this->sd32_d_datacad = $this->sd32_d_datacad_ano."-".$this->sd32_d_datacad_mes."-".$this->sd32_d_datacad_dia;
         }
       }
       $this->sd32_c_horacad = ($this->sd32_c_horacad == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_c_horacad"]:$this->sd32_c_horacad);
     }else{
       $this->sd32_i_codigo = ($this->sd32_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd32_i_codigo"]:$this->sd32_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd32_i_codigo){ 
      $this->atualizacampos();
     if($this->sd32_i_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "sd32_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd32_i_numcgs == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "sd32_i_numcgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd32_i_medico == null ){ 
       $this->sd32_i_medico = "null";
     }
     if($this->sd32_d_atendimento == null ){ 
       $this->erro_sql = " Campo Data Atendimento nao Informado.";
       $this->erro_campo = "sd32_d_atendimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd32_c_horaatend == null ){ 
       $this->erro_sql = " Campo Hora Atendimento nao Informado.";
       $this->erro_campo = "sd32_c_horaatend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd32_t_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "sd32_t_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd32_d_datacad == null ){ 
       $this->sd32_d_datacad = "now()";
     }
     if($this->sd32_c_horacad == null ){ 
       $this->sd32_c_horacad = "'||current_time||'";
     }
     if($sd32_i_codigo == "" || $sd32_i_codigo == null ){
       $result = db_query("select nextval('prontuariomedico_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontuariomedico_codigo_seq do campo: sd32_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd32_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontuariomedico_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd32_i_codigo)){
         $this->erro_sql = " Campo sd32_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd32_i_codigo = $sd32_i_codigo; 
       }
     }
     if(($this->sd32_i_codigo == null) || ($this->sd32_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd32_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontuariomedico(
                                       sd32_i_codigo 
                                      ,sd32_i_unidade 
                                      ,sd32_i_numcgs 
                                      ,sd32_i_medico 
                                      ,sd32_d_atendimento 
                                      ,sd32_c_horaatend 
                                      ,sd32_t_descricao 
                                      ,sd32_d_datacad 
                                      ,sd32_c_horacad 
                       )
                values (
                                $this->sd32_i_codigo 
                               ,$this->sd32_i_unidade 
                               ,$this->sd32_i_numcgs 
                               ,$this->sd32_i_medico 
                               ,".($this->sd32_d_atendimento == "null" || $this->sd32_d_atendimento == ""?"null":"'".$this->sd32_d_atendimento."'")." 
                               ,'$this->sd32_c_horaatend' 
                               ,'$this->sd32_t_descricao' 
                               ,".($this->sd32_d_datacad == "null" || $this->sd32_d_datacad == ""?"null":"'".$this->sd32_d_datacad."'")." 
                               ,'$this->sd32_c_horacad' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prontuário Médico ($this->sd32_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prontuário Médico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prontuário Médico ($this->sd32_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd32_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd32_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11258,'$this->sd32_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1935,11258,'','".AddSlashes(pg_result($resaco,0,'sd32_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1935,11259,'','".AddSlashes(pg_result($resaco,0,'sd32_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1935,11260,'','".AddSlashes(pg_result($resaco,0,'sd32_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1935,11267,'','".AddSlashes(pg_result($resaco,0,'sd32_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1935,11262,'','".AddSlashes(pg_result($resaco,0,'sd32_d_atendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1935,11263,'','".AddSlashes(pg_result($resaco,0,'sd32_c_horaatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1935,11261,'','".AddSlashes(pg_result($resaco,0,'sd32_t_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1935,11265,'','".AddSlashes(pg_result($resaco,0,'sd32_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1935,11266,'','".AddSlashes(pg_result($resaco,0,'sd32_c_horacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd32_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prontuariomedico set ";
     $virgula = "";
     if(trim($this->sd32_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_codigo"])){ 
       $sql  .= $virgula." sd32_i_codigo = $this->sd32_i_codigo ";
       $virgula = ",";
       if(trim($this->sd32_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd32_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd32_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_unidade"])){ 
       $sql  .= $virgula." sd32_i_unidade = $this->sd32_i_unidade ";
       $virgula = ",";
       if(trim($this->sd32_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "sd32_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd32_i_numcgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_numcgs"])){ 
       $sql  .= $virgula." sd32_i_numcgs = $this->sd32_i_numcgs ";
       $virgula = ",";
       if(trim($this->sd32_i_numcgs) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "sd32_i_numcgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd32_i_medico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_medico"])){ 
        if(trim($this->sd32_i_medico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_medico"])){ 
           $this->sd32_i_medico = "0" ; 
        } 
       $sql  .= $virgula." sd32_i_medico = $this->sd32_i_medico ";
       $virgula = ",";
     }
     if(trim($this->sd32_d_atendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_d_atendimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd32_d_atendimento_dia"] !="") ){ 
       $sql  .= $virgula." sd32_d_atendimento = '$this->sd32_d_atendimento' ";
       $virgula = ",";
       if(trim($this->sd32_d_atendimento) == null ){ 
         $this->erro_sql = " Campo Data Atendimento nao Informado.";
         $this->erro_campo = "sd32_d_atendimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_d_atendimento_dia"])){ 
         $sql  .= $virgula." sd32_d_atendimento = null ";
         $virgula = ",";
         if(trim($this->sd32_d_atendimento) == null ){ 
           $this->erro_sql = " Campo Data Atendimento nao Informado.";
           $this->erro_campo = "sd32_d_atendimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd32_c_horaatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_c_horaatend"])){ 
       $sql  .= $virgula." sd32_c_horaatend = '$this->sd32_c_horaatend' ";
       $virgula = ",";
       if(trim($this->sd32_c_horaatend) == null ){ 
         $this->erro_sql = " Campo Hora Atendimento nao Informado.";
         $this->erro_campo = "sd32_c_horaatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd32_t_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_t_descricao"])){ 
       $sql  .= $virgula." sd32_t_descricao = '$this->sd32_t_descricao' ";
       $virgula = ",";
       if(trim($this->sd32_t_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "sd32_t_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd32_d_datacad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_d_datacad_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd32_d_datacad_dia"] !="") ){ 
       $sql  .= $virgula." sd32_d_datacad = '$this->sd32_d_datacad' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_d_datacad_dia"])){ 
         $sql  .= $virgula." sd32_d_datacad = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd32_c_horacad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd32_c_horacad"])){ 
       $sql  .= $virgula." sd32_c_horacad = '$this->sd32_c_horacad' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd32_i_codigo!=null){
       $sql .= " sd32_i_codigo = $this->sd32_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd32_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11258,'$this->sd32_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1935,11258,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_i_codigo'))."','$this->sd32_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_unidade"]))
           $resac = db_query("insert into db_acount values($acount,1935,11259,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_i_unidade'))."','$this->sd32_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_numcgs"]))
           $resac = db_query("insert into db_acount values($acount,1935,11260,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_i_numcgs'))."','$this->sd32_i_numcgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_i_medico"]))
           $resac = db_query("insert into db_acount values($acount,1935,11267,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_i_medico'))."','$this->sd32_i_medico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_d_atendimento"]))
           $resac = db_query("insert into db_acount values($acount,1935,11262,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_d_atendimento'))."','$this->sd32_d_atendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_c_horaatend"]))
           $resac = db_query("insert into db_acount values($acount,1935,11263,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_c_horaatend'))."','$this->sd32_c_horaatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_t_descricao"]))
           $resac = db_query("insert into db_acount values($acount,1935,11261,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_t_descricao'))."','$this->sd32_t_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_d_datacad"]))
           $resac = db_query("insert into db_acount values($acount,1935,11265,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_d_datacad'))."','$this->sd32_d_datacad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd32_c_horacad"]))
           $resac = db_query("insert into db_acount values($acount,1935,11266,'".AddSlashes(pg_result($resaco,$conresaco,'sd32_c_horacad'))."','$this->sd32_c_horacad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuário Médico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd32_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prontuário Médico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd32_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd32_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11258,'$sd32_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1935,11258,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1935,11259,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1935,11260,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1935,11267,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1935,11262,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_d_atendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1935,11263,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_c_horaatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1935,11261,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_t_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1935,11265,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1935,11266,'','".AddSlashes(pg_result($resaco,$iresaco,'sd32_c_horacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from prontuariomedico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd32_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd32_i_codigo = $sd32_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuário Médico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd32_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prontuário Médico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd32_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prontuariomedico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd32_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontuariomedico ";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuariomedico.sd32_i_unidade";
     $sql .= "       left join medicos  on  medicos.sd03_i_codigo = prontuariomedico.sd32_i_medico";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = prontuariomedico.sd32_i_numcgs";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "       left join cgm  as a on   a.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      inner join cgs  as b on   b.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($sd32_i_codigo!=null ){
         $sql2 .= " where prontuariomedico.sd32_i_codigo = $sd32_i_codigo "; 
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
   function sql_query_file ( $sd32_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontuariomedico ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd32_i_codigo!=null ){
         $sql2 .= " where prontuariomedico.sd32_i_codigo = $sd32_i_codigo "; 
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