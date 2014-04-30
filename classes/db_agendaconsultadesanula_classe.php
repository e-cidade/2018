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
//CLASSE DA ENTIDADE agendaconsultadesanula
class cl_agendaconsultadesanula { 
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
   var $s151_i_codigo = 0; 
   var $s151_i_codigoanulamento = 0; 
   var $s151_i_agendamento = 0; 
   var $s151_d_dataanulamento_dia = null; 
   var $s151_d_dataanulamento_mes = null; 
   var $s151_d_dataanulamento_ano = null; 
   var $s151_d_dataanulamento = null; 
   var $s151_c_motivoanulamento = null; 
   var $s151_i_situacaoanulamento = 0; 
   var $s151_i_loginanulamento = 0; 
   var $s151_c_horaanulamento = null; 
   var $s151_d_datadesanulamento_dia = null; 
   var $s151_d_datadesanulamento_mes = null; 
   var $s151_d_datadesanulamento_ano = null; 
   var $s151_d_datadesanulamento = null; 
   var $s151_c_horadesanulamento = null; 
   var $s151_c_motivodesanulamento = null; 
   var $s151_i_logindesanulamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s151_i_codigo = int4 = Código 
                 s151_i_codigoanulamento = int4 = Código do anulamento 
                 s151_i_agendamento = int4 = Agendamento 
                 s151_d_dataanulamento = date = Data anulamento 
                 s151_c_motivoanulamento = varchar(100) = Motivo anulamento 
                 s151_i_situacaoanulamento = int4 = Situação do anulamento 
                 s151_i_loginanulamento = int4 = Login de quem anulou 
                 s151_c_horaanulamento = varchar(5) = Hora anulamento 
                 s151_d_datadesanulamento = date = Data desanulamento 
                 s151_c_horadesanulamento = varchar(5) = Hora desanulamento 
                 s151_c_motivodesanulamento = varchar(100) = Motivo desanulamento 
                 s151_i_logindesanulamento = int4 = Login desanulamento 
                 ";
   //funcao construtor da classe 
   function cl_agendaconsultadesanula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agendaconsultadesanula"); 
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
       $this->s151_i_codigo = ($this->s151_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_i_codigo"]:$this->s151_i_codigo);
       $this->s151_i_codigoanulamento = ($this->s151_i_codigoanulamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_i_codigoanulamento"]:$this->s151_i_codigoanulamento);
       $this->s151_i_agendamento = ($this->s151_i_agendamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_i_agendamento"]:$this->s151_i_agendamento);
       if($this->s151_d_dataanulamento == ""){
         $this->s151_d_dataanulamento_dia = ($this->s151_d_dataanulamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_d_dataanulamento_dia"]:$this->s151_d_dataanulamento_dia);
         $this->s151_d_dataanulamento_mes = ($this->s151_d_dataanulamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_d_dataanulamento_mes"]:$this->s151_d_dataanulamento_mes);
         $this->s151_d_dataanulamento_ano = ($this->s151_d_dataanulamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_d_dataanulamento_ano"]:$this->s151_d_dataanulamento_ano);
         if($this->s151_d_dataanulamento_dia != ""){
            $this->s151_d_dataanulamento = $this->s151_d_dataanulamento_ano."-".$this->s151_d_dataanulamento_mes."-".$this->s151_d_dataanulamento_dia;
         }
       }
       $this->s151_c_motivoanulamento = ($this->s151_c_motivoanulamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_c_motivoanulamento"]:$this->s151_c_motivoanulamento);
       $this->s151_i_situacaoanulamento = ($this->s151_i_situacaoanulamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_i_situacaoanulamento"]:$this->s151_i_situacaoanulamento);
       $this->s151_i_loginanulamento = ($this->s151_i_loginanulamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_i_loginanulamento"]:$this->s151_i_loginanulamento);
       $this->s151_c_horaanulamento = ($this->s151_c_horaanulamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_c_horaanulamento"]:$this->s151_c_horaanulamento);
       if($this->s151_d_datadesanulamento == ""){
         $this->s151_d_datadesanulamento_dia = ($this->s151_d_datadesanulamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_d_datadesanulamento_dia"]:$this->s151_d_datadesanulamento_dia);
         $this->s151_d_datadesanulamento_mes = ($this->s151_d_datadesanulamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_d_datadesanulamento_mes"]:$this->s151_d_datadesanulamento_mes);
         $this->s151_d_datadesanulamento_ano = ($this->s151_d_datadesanulamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_d_datadesanulamento_ano"]:$this->s151_d_datadesanulamento_ano);
         if($this->s151_d_datadesanulamento_dia != ""){
            $this->s151_d_datadesanulamento = $this->s151_d_datadesanulamento_ano."-".$this->s151_d_datadesanulamento_mes."-".$this->s151_d_datadesanulamento_dia;
         }
       }
       $this->s151_c_horadesanulamento = ($this->s151_c_horadesanulamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_c_horadesanulamento"]:$this->s151_c_horadesanulamento);
       $this->s151_c_motivodesanulamento = ($this->s151_c_motivodesanulamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_c_motivodesanulamento"]:$this->s151_c_motivodesanulamento);
       $this->s151_i_logindesanulamento = ($this->s151_i_logindesanulamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_i_logindesanulamento"]:$this->s151_i_logindesanulamento);
     }else{
       $this->s151_i_codigo = ($this->s151_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s151_i_codigo"]:$this->s151_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s151_i_codigo){ 
      $this->atualizacampos();
     if($this->s151_i_codigoanulamento == null ){ 
       $this->erro_sql = " Campo Código do anulamento nao Informado.";
       $this->erro_campo = "s151_i_codigoanulamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s151_i_agendamento == null ){ 
       $this->erro_sql = " Campo Agendamento nao Informado.";
       $this->erro_campo = "s151_i_agendamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s151_d_dataanulamento == null ){ 
       $this->erro_sql = " Campo Data anulamento nao Informado.";
       $this->erro_campo = "s151_d_dataanulamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s151_c_motivoanulamento == null ){ 
       $this->s151_c_motivoanulamento = "null";
     }
     if($this->s151_i_situacaoanulamento == null ){ 
       $this->erro_sql = " Campo Situação do anulamento nao Informado.";
       $this->erro_campo = "s151_i_situacaoanulamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s151_i_loginanulamento == null ){ 
       $this->erro_sql = " Campo Login de quem anulou nao Informado.";
       $this->erro_campo = "s151_i_loginanulamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s151_d_datadesanulamento == null ){ 
       $this->erro_sql = " Campo Data desanulamento nao Informado.";
       $this->erro_campo = "s151_d_datadesanulamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s151_c_horadesanulamento == null ){ 
       $this->erro_sql = " Campo Hora desanulamento nao Informado.";
       $this->erro_campo = "s151_c_horadesanulamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s151_c_motivodesanulamento == null ){ 
       $this->erro_sql = " Campo Motivo desanulamento nao Informado.";
       $this->erro_campo = "s151_c_motivodesanulamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s151_i_logindesanulamento == null ){ 
       $this->erro_sql = " Campo Login desanulamento nao Informado.";
       $this->erro_campo = "s151_i_logindesanulamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s151_i_codigo == "" || $s151_i_codigo == null ){
       $result = db_query("select nextval('agendaconsultadesanula_s151_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agendaconsultadesanula_s151_i_codigo_seq do campo: s151_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s151_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agendaconsultadesanula_s151_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s151_i_codigo)){
         $this->erro_sql = " Campo s151_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s151_i_codigo = $s151_i_codigo; 
       }
     }
     if(($this->s151_i_codigo == null) || ($this->s151_i_codigo == "") ){ 
       $this->erro_sql = " Campo s151_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agendaconsultadesanula(
                                       s151_i_codigo 
                                      ,s151_i_codigoanulamento 
                                      ,s151_i_agendamento 
                                      ,s151_d_dataanulamento 
                                      ,s151_c_motivoanulamento 
                                      ,s151_i_situacaoanulamento 
                                      ,s151_i_loginanulamento 
                                      ,s151_c_horaanulamento 
                                      ,s151_d_datadesanulamento 
                                      ,s151_c_horadesanulamento 
                                      ,s151_c_motivodesanulamento 
                                      ,s151_i_logindesanulamento 
                       )
                values (
                                $this->s151_i_codigo 
                               ,$this->s151_i_codigoanulamento 
                               ,$this->s151_i_agendamento 
                               ,".($this->s151_d_dataanulamento == "null" || $this->s151_d_dataanulamento == ""?"null":"'".$this->s151_d_dataanulamento."'")." 
                               ,'$this->s151_c_motivoanulamento' 
                               ,$this->s151_i_situacaoanulamento 
                               ,$this->s151_i_loginanulamento 
                               ,'$this->s151_c_horaanulamento' 
                               ,".($this->s151_d_datadesanulamento == "null" || $this->s151_d_datadesanulamento == ""?"null":"'".$this->s151_d_datadesanulamento."'")." 
                               ,'$this->s151_c_horadesanulamento' 
                               ,'$this->s151_c_motivodesanulamento' 
                               ,$this->s151_i_logindesanulamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "agendaconsultadesanula ($this->s151_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "agendaconsultadesanula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "agendaconsultadesanula ($this->s151_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s151_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s151_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15684,'$this->s151_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2751,15684,'','".AddSlashes(pg_result($resaco,0,'s151_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15685,'','".AddSlashes(pg_result($resaco,0,'s151_i_codigoanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15686,'','".AddSlashes(pg_result($resaco,0,'s151_i_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15687,'','".AddSlashes(pg_result($resaco,0,'s151_d_dataanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15688,'','".AddSlashes(pg_result($resaco,0,'s151_c_motivoanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15689,'','".AddSlashes(pg_result($resaco,0,'s151_i_situacaoanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15690,'','".AddSlashes(pg_result($resaco,0,'s151_i_loginanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15691,'','".AddSlashes(pg_result($resaco,0,'s151_c_horaanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15692,'','".AddSlashes(pg_result($resaco,0,'s151_d_datadesanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15693,'','".AddSlashes(pg_result($resaco,0,'s151_c_horadesanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15694,'','".AddSlashes(pg_result($resaco,0,'s151_c_motivodesanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2751,15695,'','".AddSlashes(pg_result($resaco,0,'s151_i_logindesanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s151_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update agendaconsultadesanula set ";
     $virgula = "";
     if(trim($this->s151_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_i_codigo"])){ 
       $sql  .= $virgula." s151_i_codigo = $this->s151_i_codigo ";
       $virgula = ",";
       if(trim($this->s151_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s151_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s151_i_codigoanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_i_codigoanulamento"])){ 
       $sql  .= $virgula." s151_i_codigoanulamento = $this->s151_i_codigoanulamento ";
       $virgula = ",";
       if(trim($this->s151_i_codigoanulamento) == null ){ 
         $this->erro_sql = " Campo Código do anulamento nao Informado.";
         $this->erro_campo = "s151_i_codigoanulamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s151_i_agendamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_i_agendamento"])){ 
       $sql  .= $virgula." s151_i_agendamento = $this->s151_i_agendamento ";
       $virgula = ",";
       if(trim($this->s151_i_agendamento) == null ){ 
         $this->erro_sql = " Campo Agendamento nao Informado.";
         $this->erro_campo = "s151_i_agendamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s151_d_dataanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_d_dataanulamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s151_d_dataanulamento_dia"] !="") ){ 
       $sql  .= $virgula." s151_d_dataanulamento = '$this->s151_d_dataanulamento' ";
       $virgula = ",";
       if(trim($this->s151_d_dataanulamento) == null ){ 
         $this->erro_sql = " Campo Data anulamento nao Informado.";
         $this->erro_campo = "s151_d_dataanulamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s151_d_dataanulamento_dia"])){ 
         $sql  .= $virgula." s151_d_dataanulamento = null ";
         $virgula = ",";
         if(trim($this->s151_d_dataanulamento) == null ){ 
           $this->erro_sql = " Campo Data anulamento nao Informado.";
           $this->erro_campo = "s151_d_dataanulamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s151_c_motivoanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_c_motivoanulamento"])){ 
       $sql  .= $virgula." s151_c_motivoanulamento = '$this->s151_c_motivoanulamento' ";
       $virgula = ",";
     }
     if(trim($this->s151_i_situacaoanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_i_situacaoanulamento"])){ 
       $sql  .= $virgula." s151_i_situacaoanulamento = $this->s151_i_situacaoanulamento ";
       $virgula = ",";
       if(trim($this->s151_i_situacaoanulamento) == null ){ 
         $this->erro_sql = " Campo Situação do anulamento nao Informado.";
         $this->erro_campo = "s151_i_situacaoanulamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s151_i_loginanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_i_loginanulamento"])){ 
       $sql  .= $virgula." s151_i_loginanulamento = $this->s151_i_loginanulamento ";
       $virgula = ",";
       if(trim($this->s151_i_loginanulamento) == null ){ 
         $this->erro_sql = " Campo Login de quem anulou nao Informado.";
         $this->erro_campo = "s151_i_loginanulamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s151_c_horaanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_c_horaanulamento"])){ 
       $sql  .= $virgula." s151_c_horaanulamento = '$this->s151_c_horaanulamento' ";
       $virgula = ",";
     }
     if(trim($this->s151_d_datadesanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_d_datadesanulamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s151_d_datadesanulamento_dia"] !="") ){ 
       $sql  .= $virgula." s151_d_datadesanulamento = '$this->s151_d_datadesanulamento' ";
       $virgula = ",";
       if(trim($this->s151_d_datadesanulamento) == null ){ 
         $this->erro_sql = " Campo Data desanulamento nao Informado.";
         $this->erro_campo = "s151_d_datadesanulamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s151_d_datadesanulamento_dia"])){ 
         $sql  .= $virgula." s151_d_datadesanulamento = null ";
         $virgula = ",";
         if(trim($this->s151_d_datadesanulamento) == null ){ 
           $this->erro_sql = " Campo Data desanulamento nao Informado.";
           $this->erro_campo = "s151_d_datadesanulamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s151_c_horadesanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_c_horadesanulamento"])){ 
       $sql  .= $virgula." s151_c_horadesanulamento = '$this->s151_c_horadesanulamento' ";
       $virgula = ",";
       if(trim($this->s151_c_horadesanulamento) == null ){ 
         $this->erro_sql = " Campo Hora desanulamento nao Informado.";
         $this->erro_campo = "s151_c_horadesanulamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s151_c_motivodesanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_c_motivodesanulamento"])){ 
       $sql  .= $virgula." s151_c_motivodesanulamento = '$this->s151_c_motivodesanulamento' ";
       $virgula = ",";
       if(trim($this->s151_c_motivodesanulamento) == null ){ 
         $this->erro_sql = " Campo Motivo desanulamento nao Informado.";
         $this->erro_campo = "s151_c_motivodesanulamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s151_i_logindesanulamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s151_i_logindesanulamento"])){ 
       $sql  .= $virgula." s151_i_logindesanulamento = $this->s151_i_logindesanulamento ";
       $virgula = ",";
       if(trim($this->s151_i_logindesanulamento) == null ){ 
         $this->erro_sql = " Campo Login desanulamento nao Informado.";
         $this->erro_campo = "s151_i_logindesanulamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s151_i_codigo!=null){
       $sql .= " s151_i_codigo = $this->s151_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s151_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15684,'$this->s151_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_i_codigo"]) || $this->s151_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2751,15684,'".AddSlashes(pg_result($resaco,$conresaco,'s151_i_codigo'))."','$this->s151_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_i_codigoanulamento"]) || $this->s151_i_codigoanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15685,'".AddSlashes(pg_result($resaco,$conresaco,'s151_i_codigoanulamento'))."','$this->s151_i_codigoanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_i_agendamento"]) || $this->s151_i_agendamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15686,'".AddSlashes(pg_result($resaco,$conresaco,'s151_i_agendamento'))."','$this->s151_i_agendamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_d_dataanulamento"]) || $this->s151_d_dataanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15687,'".AddSlashes(pg_result($resaco,$conresaco,'s151_d_dataanulamento'))."','$this->s151_d_dataanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_c_motivoanulamento"]) || $this->s151_c_motivoanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15688,'".AddSlashes(pg_result($resaco,$conresaco,'s151_c_motivoanulamento'))."','$this->s151_c_motivoanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_i_situacaoanulamento"]) || $this->s151_i_situacaoanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15689,'".AddSlashes(pg_result($resaco,$conresaco,'s151_i_situacaoanulamento'))."','$this->s151_i_situacaoanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_i_loginanulamento"]) || $this->s151_i_loginanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15690,'".AddSlashes(pg_result($resaco,$conresaco,'s151_i_loginanulamento'))."','$this->s151_i_loginanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_c_horaanulamento"]) || $this->s151_c_horaanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15691,'".AddSlashes(pg_result($resaco,$conresaco,'s151_c_horaanulamento'))."','$this->s151_c_horaanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_d_datadesanulamento"]) || $this->s151_d_datadesanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15692,'".AddSlashes(pg_result($resaco,$conresaco,'s151_d_datadesanulamento'))."','$this->s151_d_datadesanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_c_horadesanulamento"]) || $this->s151_c_horadesanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15693,'".AddSlashes(pg_result($resaco,$conresaco,'s151_c_horadesanulamento'))."','$this->s151_c_horadesanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_c_motivodesanulamento"]) || $this->s151_c_motivodesanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15694,'".AddSlashes(pg_result($resaco,$conresaco,'s151_c_motivodesanulamento'))."','$this->s151_c_motivodesanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s151_i_logindesanulamento"]) || $this->s151_i_logindesanulamento != "")
           $resac = db_query("insert into db_acount values($acount,2751,15695,'".AddSlashes(pg_result($resaco,$conresaco,'s151_i_logindesanulamento'))."','$this->s151_i_logindesanulamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "agendaconsultadesanula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s151_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "agendaconsultadesanula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s151_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s151_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s151_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s151_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15684,'$s151_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2751,15684,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15685,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_i_codigoanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15686,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_i_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15687,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_d_dataanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15688,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_c_motivoanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15689,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_i_situacaoanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15690,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_i_loginanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15691,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_c_horaanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15692,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_d_datadesanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15693,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_c_horadesanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15694,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_c_motivodesanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2751,15695,'','".AddSlashes(pg_result($resaco,$iresaco,'s151_i_logindesanulamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agendaconsultadesanula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s151_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s151_i_codigo = $s151_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "agendaconsultadesanula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s151_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "agendaconsultadesanula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s151_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s151_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:agendaconsultadesanula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s151_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendaconsultadesanula ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendaconsultadesanula.s151_i_logindesanulamento";
     $sql2 = "";
     if($dbwhere==""){
       if($s151_i_codigo!=null ){
         $sql2 .= " where agendaconsultadesanula.s151_i_codigo = $s151_i_codigo "; 
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
   function sql_query_file ( $s151_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendaconsultadesanula ";
     $sql2 = "";
     if($dbwhere==""){
       if($s151_i_codigo!=null ){
         $sql2 .= " where agendaconsultadesanula.s151_i_codigo = $s151_i_codigo "; 
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