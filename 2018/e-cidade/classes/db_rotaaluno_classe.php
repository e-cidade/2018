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

//MODULO: educação
//CLASSE DA ENTIDADE rotaaluno
class cl_rotaaluno { 
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
   var $ed219_i_codigo = 0; 
   var $ed219_d_dataacao_dia = null; 
   var $ed219_d_dataacao_mes = null; 
   var $ed219_d_dataacao_ano = null; 
   var $ed219_d_dataacao = null; 
   var $ed219_d_datafim_dia = null; 
   var $ed219_d_datafim_mes = null; 
   var $ed219_d_datafim_ano = null; 
   var $ed219_d_datafim = null; 
   var $ed219_d_datainicio_dia = null; 
   var $ed219_d_datainicio_mes = null; 
   var $ed219_d_datainicio_ano = null; 
   var $ed219_d_datainicio = null; 
   var $ed219_i_rota = 0; 
   var $ed219_i_aluno = 0; 
   var $ed219_i_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed219_i_codigo = int4 = Código 
                 ed219_d_dataacao = date = Data ação 
                 ed219_d_datafim = date = Data Final 
                 ed219_d_datainicio = date = Data Inicial 
                 ed219_i_rota = int4 = Rota 
                 ed219_i_aluno = int4 = Aluno 
                 ed219_i_usuario = int4 = Usuario 
                 ";
   //funcao construtor da classe 
   function cl_rotaaluno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rotaaluno"); 
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
       $this->ed219_i_codigo = ($this->ed219_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_codigo"]:$this->ed219_i_codigo);
       if($this->ed219_d_dataacao == ""){
         $this->ed219_d_dataacao_dia = ($this->ed219_d_dataacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_dataacao_dia"]:$this->ed219_d_dataacao_dia);
         $this->ed219_d_dataacao_mes = ($this->ed219_d_dataacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_dataacao_mes"]:$this->ed219_d_dataacao_mes);
         $this->ed219_d_dataacao_ano = ($this->ed219_d_dataacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_dataacao_ano"]:$this->ed219_d_dataacao_ano);
         if($this->ed219_d_dataacao_dia != ""){
            $this->ed219_d_dataacao = $this->ed219_d_dataacao_ano."-".$this->ed219_d_dataacao_mes."-".$this->ed219_d_dataacao_dia;
         }
       }
       if($this->ed219_d_datafim == ""){
         $this->ed219_d_datafim_dia = ($this->ed219_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_datafim_dia"]:$this->ed219_d_datafim_dia);
         $this->ed219_d_datafim_mes = ($this->ed219_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_datafim_mes"]:$this->ed219_d_datafim_mes);
         $this->ed219_d_datafim_ano = ($this->ed219_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_datafim_ano"]:$this->ed219_d_datafim_ano);
         if($this->ed219_d_datafim_dia != ""){
            $this->ed219_d_datafim = $this->ed219_d_datafim_ano."-".$this->ed219_d_datafim_mes."-".$this->ed219_d_datafim_dia;
         }
       }
       if($this->ed219_d_datainicio == ""){
         $this->ed219_d_datainicio_dia = ($this->ed219_d_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_datainicio_dia"]:$this->ed219_d_datainicio_dia);
         $this->ed219_d_datainicio_mes = ($this->ed219_d_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_datainicio_mes"]:$this->ed219_d_datainicio_mes);
         $this->ed219_d_datainicio_ano = ($this->ed219_d_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_d_datainicio_ano"]:$this->ed219_d_datainicio_ano);
         if($this->ed219_d_datainicio_dia != ""){
            $this->ed219_d_datainicio = $this->ed219_d_datainicio_ano."-".$this->ed219_d_datainicio_mes."-".$this->ed219_d_datainicio_dia;
         }
       }
       $this->ed219_i_rota = ($this->ed219_i_rota == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_rota"]:$this->ed219_i_rota);
       $this->ed219_i_aluno = ($this->ed219_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_aluno"]:$this->ed219_i_aluno);
       $this->ed219_i_usuario = ($this->ed219_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_usuario"]:$this->ed219_i_usuario);
     }else{
       $this->ed219_i_codigo = ($this->ed219_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed219_i_codigo"]:$this->ed219_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed219_i_codigo){ 
      $this->atualizacampos();
     if($this->ed219_d_dataacao == null ){ 
       $this->erro_sql = " Campo Data ação nao Informado.";
       $this->erro_campo = "ed219_d_dataacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed219_d_datafim == null ){ 
       $this->ed219_d_datafim = "null";
     }
     if($this->ed219_d_datainicio == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ed219_d_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed219_i_rota == null ){ 
       $this->erro_sql = " Campo Rota nao Informado.";
       $this->erro_campo = "ed219_i_rota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed219_i_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed219_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed219_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "ed219_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed219_i_codigo == "" || $ed219_i_codigo == null ){
       $result = db_query("select nextval('rotaaluno_ed219_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rotaaluno_ed219_i_codigo_seq do campo: ed219_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed219_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rotaaluno_ed219_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed219_i_codigo)){
         $this->erro_sql = " Campo ed219_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed219_i_codigo = $ed219_i_codigo; 
       }
     }
     if(($this->ed219_i_codigo == null) || ($this->ed219_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed219_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rotaaluno(
                                       ed219_i_codigo 
                                      ,ed219_d_dataacao 
                                      ,ed219_d_datafim 
                                      ,ed219_d_datainicio 
                                      ,ed219_i_rota 
                                      ,ed219_i_aluno 
                                      ,ed219_i_usuario 
                       )
                values (
                                $this->ed219_i_codigo 
                               ,".($this->ed219_d_dataacao == "null" || $this->ed219_d_dataacao == ""?"null":"'".$this->ed219_d_dataacao."'")." 
                               ,".($this->ed219_d_datafim == "null" || $this->ed219_d_datafim == ""?"null":"'".$this->ed219_d_datafim."'")." 
                               ,".($this->ed219_d_datainicio == "null" || $this->ed219_d_datainicio == ""?"null":"'".$this->ed219_d_datainicio."'")." 
                               ,$this->ed219_i_rota 
                               ,$this->ed219_i_aluno 
                               ,$this->ed219_i_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rotaaluno ($this->ed219_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rotaaluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rotaaluno ($this->ed219_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed219_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed219_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11161,'$this->ed219_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1927,11161,'','".AddSlashes(pg_result($resaco,0,'ed219_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1927,11162,'','".AddSlashes(pg_result($resaco,0,'ed219_d_dataacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1927,11163,'','".AddSlashes(pg_result($resaco,0,'ed219_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1927,11164,'','".AddSlashes(pg_result($resaco,0,'ed219_d_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1927,11165,'','".AddSlashes(pg_result($resaco,0,'ed219_i_rota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1927,11166,'','".AddSlashes(pg_result($resaco,0,'ed219_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1927,11167,'','".AddSlashes(pg_result($resaco,0,'ed219_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed219_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rotaaluno set ";
     $virgula = "";
     if(trim($this->ed219_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_codigo"])){ 
       $sql  .= $virgula." ed219_i_codigo = $this->ed219_i_codigo ";
       $virgula = ",";
       if(trim($this->ed219_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed219_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed219_d_dataacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_dataacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed219_d_dataacao_dia"] !="") ){ 
       $sql  .= $virgula." ed219_d_dataacao = '$this->ed219_d_dataacao' ";
       $virgula = ",";
       if(trim($this->ed219_d_dataacao) == null ){ 
         $this->erro_sql = " Campo Data ação nao Informado.";
         $this->erro_campo = "ed219_d_dataacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_dataacao_dia"])){ 
         $sql  .= $virgula." ed219_d_dataacao = null ";
         $virgula = ",";
         if(trim($this->ed219_d_dataacao) == null ){ 
           $this->erro_sql = " Campo Data ação nao Informado.";
           $this->erro_campo = "ed219_d_dataacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed219_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed219_d_datafim_dia"] !="") ){ 
       $sql  .= $virgula." ed219_d_datafim = '$this->ed219_d_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_datafim_dia"])){ 
         $sql  .= $virgula." ed219_d_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed219_d_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed219_d_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." ed219_d_datainicio = '$this->ed219_d_datainicio' ";
       $virgula = ",";
       if(trim($this->ed219_d_datainicio) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ed219_d_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_datainicio_dia"])){ 
         $sql  .= $virgula." ed219_d_datainicio = null ";
         $virgula = ",";
         if(trim($this->ed219_d_datainicio) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ed219_d_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed219_i_rota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_rota"])){ 
       $sql  .= $virgula." ed219_i_rota = $this->ed219_i_rota ";
       $virgula = ",";
       if(trim($this->ed219_i_rota) == null ){ 
         $this->erro_sql = " Campo Rota nao Informado.";
         $this->erro_campo = "ed219_i_rota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed219_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_aluno"])){ 
       $sql  .= $virgula." ed219_i_aluno = $this->ed219_i_aluno ";
       $virgula = ",";
       if(trim($this->ed219_i_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed219_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed219_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_usuario"])){ 
       $sql  .= $virgula." ed219_i_usuario = $this->ed219_i_usuario ";
       $virgula = ",";
       if(trim($this->ed219_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "ed219_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed219_i_codigo!=null){
       $sql .= " ed219_i_codigo = $this->ed219_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed219_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11161,'$this->ed219_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1927,11161,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_i_codigo'))."','$this->ed219_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_dataacao"]))
           $resac = db_query("insert into db_acount values($acount,1927,11162,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_d_dataacao'))."','$this->ed219_d_dataacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_datafim"]))
           $resac = db_query("insert into db_acount values($acount,1927,11163,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_d_datafim'))."','$this->ed219_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_d_datainicio"]))
           $resac = db_query("insert into db_acount values($acount,1927,11164,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_d_datainicio'))."','$this->ed219_d_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_rota"]))
           $resac = db_query("insert into db_acount values($acount,1927,11165,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_i_rota'))."','$this->ed219_i_rota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_aluno"]))
           $resac = db_query("insert into db_acount values($acount,1927,11166,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_i_aluno'))."','$this->ed219_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed219_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1927,11167,'".AddSlashes(pg_result($resaco,$conresaco,'ed219_i_usuario'))."','$this->ed219_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rotaaluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed219_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rotaaluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed219_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed219_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed219_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed219_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11161,'$ed219_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1927,11161,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1927,11162,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_d_dataacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1927,11163,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1927,11164,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_d_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1927,11165,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_i_rota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1927,11166,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1927,11167,'','".AddSlashes(pg_result($resaco,$iresaco,'ed219_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rotaaluno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed219_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed219_i_codigo = $ed219_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rotaaluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed219_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rotaaluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed219_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed219_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rotaaluno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed219_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rotaaluno ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rotaaluno.ed219_i_usuario";
     $sql .= "      inner join rota  on  rota.ed217_i_codigo = rotaaluno.ed219_i_rota";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = rotaaluno.ed219_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed219_i_codigo!=null ){
         $sql2 .= " where rotaaluno.ed219_i_codigo = $ed219_i_codigo "; 
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
   function sql_query_file ( $ed219_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rotaaluno ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed219_i_codigo!=null ){
         $sql2 .= " where rotaaluno.ed219_i_codigo = $ed219_i_codigo "; 
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