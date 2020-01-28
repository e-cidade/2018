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

//MODULO: educação
//CLASSE DA ENTIDADE periodoescola
class cl_periodoescola { 
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
   var $ed17_i_codigo = 0; 
   var $ed17_i_escola = 0; 
   var $ed17_i_turno = 0; 
   var $ed17_i_periodoaula = 0; 
   var $ed17_h_inicio = null; 
   var $ed17_h_fim = null; 
   var $ed17_duracao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed17_i_codigo = int8 = Código do Período 
                 ed17_i_escola = int8 = Escola 
                 ed17_i_turno = int8 = Turno 
                 ed17_i_periodoaula = int8 = Periodo de Aula 
                 ed17_h_inicio = char(5) = Hora Início 
                 ed17_h_fim = char(5) = Hora Término 
                 ed17_duracao = varchar(5) = Duração 
                 ";
   //funcao construtor da classe 
   function cl_periodoescola() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("periodoescola"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed17_i_escola=".@$GLOBALS["HTTP_POST_VARS"]["ed17_i_escola"]."&descrdepto=".@$GLOBALS["HTTP_POST_VARS"]["descrdepto"]);
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
       $this->ed17_i_codigo = ($this->ed17_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_codigo"]:$this->ed17_i_codigo);
       $this->ed17_i_escola = ($this->ed17_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_escola"]:$this->ed17_i_escola);
       $this->ed17_i_turno = ($this->ed17_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_turno"]:$this->ed17_i_turno);
       $this->ed17_i_periodoaula = ($this->ed17_i_periodoaula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_periodoaula"]:$this->ed17_i_periodoaula);
       $this->ed17_h_inicio = ($this->ed17_h_inicio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_h_inicio"]:$this->ed17_h_inicio);
       $this->ed17_h_fim = ($this->ed17_h_fim == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_h_fim"]:$this->ed17_h_fim);
       $this->ed17_duracao = ($this->ed17_duracao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_duracao"]:$this->ed17_duracao);
     }else{
       $this->ed17_i_codigo = ($this->ed17_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_codigo"]:$this->ed17_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed17_i_codigo){ 
      $this->atualizacampos();
     if($this->ed17_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed17_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_i_turno == null ){ 
       $this->erro_sql = " Campo Turno nao Informado.";
       $this->erro_campo = "ed17_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_i_periodoaula == null ){ 
       $this->erro_sql = " Campo Periodo de Aula nao Informado.";
       $this->erro_campo = "ed17_i_periodoaula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_h_inicio == null ){ 
       $this->erro_sql = " Campo Hora Início nao Informado.";
       $this->erro_campo = "ed17_h_inicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_h_fim == null ){ 
       $this->erro_sql = " Campo Hora Término nao Informado.";
       $this->erro_campo = "ed17_h_fim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_duracao == null ){ 
       $this->erro_sql = " Campo Duração não informado.";
       $this->erro_campo = "ed17_duracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed17_i_codigo == "" || $ed17_i_codigo == null ){
       $result = db_query("select nextval('periodoescola_ed17_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: periodoescola_ed17_i_codigo_seq do campo: ed17_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed17_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from periodoescola_ed17_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed17_i_codigo)){
         $this->erro_sql = " Campo ed17_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed17_i_codigo = $ed17_i_codigo; 
       }
     }
     if(($this->ed17_i_codigo == null) || ($this->ed17_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed17_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into periodoescola(
                                       ed17_i_codigo 
                                      ,ed17_i_escola 
                                      ,ed17_i_turno 
                                      ,ed17_i_periodoaula 
                                      ,ed17_h_inicio 
                                      ,ed17_h_fim 
                                      ,ed17_duracao 
                       )
                values (
                                $this->ed17_i_codigo 
                               ,$this->ed17_i_escola 
                               ,$this->ed17_i_turno 
                               ,$this->ed17_i_periodoaula 
                               ,'$this->ed17_h_inicio' 
                               ,'$this->ed17_h_fim' 
                               ,'$this->ed17_duracao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Períodos da Escola ($this->ed17_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Períodos da Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Períodos da Escola ($this->ed17_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed17_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     $resaco = $this->sql_record($this->sql_query_file($this->ed17_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008243,'$this->ed17_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010040,1008243,'','".AddSlashes(pg_result($resaco,0,'ed17_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010040,1008244,'','".AddSlashes(pg_result($resaco,0,'ed17_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010040,1008248,'','".AddSlashes(pg_result($resaco,0,'ed17_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010040,1008245,'','".AddSlashes(pg_result($resaco,0,'ed17_i_periodoaula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010040,1008246,'','".AddSlashes(pg_result($resaco,0,'ed17_h_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010040,1008247,'','".AddSlashes(pg_result($resaco,0,'ed17_h_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010040,20756,'','".AddSlashes(pg_result($resaco,0,'ed17_duracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed17_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update periodoescola set ";
     $virgula = "";
     if(trim($this->ed17_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_codigo"])){ 
       $sql  .= $virgula." ed17_i_codigo = $this->ed17_i_codigo ";
       $virgula = ",";
       if(trim($this->ed17_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Período nao Informado.";
         $this->erro_campo = "ed17_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_escola"])){ 
       $sql  .= $virgula." ed17_i_escola = $this->ed17_i_escola ";
       $virgula = ",";
       if(trim($this->ed17_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed17_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_turno"])){ 
       $sql  .= $virgula." ed17_i_turno = $this->ed17_i_turno ";
       $virgula = ",";
       if(trim($this->ed17_i_turno) == null ){ 
         $this->erro_sql = " Campo Turno nao Informado.";
         $this->erro_campo = "ed17_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_i_periodoaula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_periodoaula"])){ 
       $sql  .= $virgula." ed17_i_periodoaula = $this->ed17_i_periodoaula ";
       $virgula = ",";
       if(trim($this->ed17_i_periodoaula) == null ){ 
         $this->erro_sql = " Campo Periodo de Aula nao Informado.";
         $this->erro_campo = "ed17_i_periodoaula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_h_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_h_inicio"])){ 
       $sql  .= $virgula." ed17_h_inicio = '$this->ed17_h_inicio' ";
       $virgula = ",";
       if(trim($this->ed17_h_inicio) == null ){ 
         $this->erro_sql = " Campo Hora Início nao Informado.";
         $this->erro_campo = "ed17_h_inicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_h_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_h_fim"])){ 
       $sql  .= $virgula." ed17_h_fim = '$this->ed17_h_fim' ";
       $virgula = ",";
       if(trim($this->ed17_h_fim) == null ){ 
         $this->erro_sql = " Campo Hora Término nao Informado.";
         $this->erro_campo = "ed17_h_fim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_duracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_duracao"])){ 
       $sql  .= $virgula." ed17_duracao = '$this->ed17_duracao' ";
       $virgula = ",";
       if(trim($this->ed17_duracao) == null ){ 
         $this->erro_sql = " Campo Duração não informado.";
         $this->erro_campo = "ed17_duracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed17_i_codigo!=null){
       $sql .= " ed17_i_codigo = $this->ed17_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     $resaco = $this->sql_record($this->sql_query_file($this->ed17_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008243,'$this->ed17_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010040,1008243,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_i_codigo'))."','$this->ed17_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010040,1008244,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_i_escola'))."','$this->ed17_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_turno"]))
           $resac = db_query("insert into db_acount values($acount,1010040,1008248,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_i_turno'))."','$this->ed17_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_periodoaula"]))
           $resac = db_query("insert into db_acount values($acount,1010040,1008245,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_i_periodoaula'))."','$this->ed17_i_periodoaula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_h_inicio"]))
           $resac = db_query("insert into db_acount values($acount,1010040,1008246,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_h_inicio'))."','$this->ed17_h_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_h_fim"]))
           $resac = db_query("insert into db_acount values($acount,1010040,1008247,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_h_fim'))."','$this->ed17_h_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed17_duracao"]) || $this->ed17_duracao != "")
             $resac = db_query("insert into db_acount values($acount,1010040,20756,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_duracao'))."','$this->ed17_duracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Períodos da Escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed17_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Períodos da Escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed17_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed17_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008243,'$ed17_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010040,1008243,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010040,1008244,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010040,1008248,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010040,1008245,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_i_periodoaula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010040,1008246,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_h_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010040,1008247,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_h_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010040,20756,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_duracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
       }
     }
     $sql = " delete from periodoescola
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed17_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed17_i_codigo = $ed17_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Períodos da Escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed17_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Períodos da Escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed17_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:periodoescola";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodoescola ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
     $sql .= "      inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed17_i_codigo!=null ){
         $sql2 .= " where periodoescola.ed17_i_codigo = $ed17_i_codigo "; 
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
   function sql_query_file ( $ed17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodoescola ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed17_i_codigo!=null ){
         $sql2 .= " where periodoescola.ed17_i_codigo = $ed17_i_codigo "; 
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

  /**
   * Query contendo os possíveis vínculos com o periodoescola
   * @param  integer $ed17_i_codigo
   * @param  string $campos
   * @param  string $ordem
   * @param  string $dbwhere
   * @return string
   */
  function sql_query_vinculos( $ed17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodoescola ";
     $sql .= "      left join rechumanohoradisp        on rechumanohoradisp.ed33_i_periodo       = periodoescola.ed17_i_codigo";
     $sql .= "      left join regenciahorario          on regenciahorario.ed58_i_periodo         = periodoescola.ed17_i_codigo";
     $sql .= "      left join regenciahorariohistorico on regenciahorariohistorico.ed323_periodo = periodoescola.ed17_i_codigo";
     $sql .= "      left join turmaachorario           on turmaachorario.ed270_i_periodo         = periodoescola.ed17_i_codigo";
     $sql .= "      left join cursoturno               on cursoturno.ed85_i_turno                = periodoescola.ed17_i_turno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed17_i_codigo!=null ){
         $sql2 .= " where periodoescola.ed17_i_codigo = $ed17_i_codigo "; 
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