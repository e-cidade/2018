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
//CLASSE DA ENTIDADE progconfig
class cl_progconfig { 
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
   var $ed110_i_codigo = 0; 
   var $ed110_i_usuario = 0; 
   var $ed110_i_inicio = 0; 
   var $ed110_i_intervalo = 0; 
   var $ed110_i_ptgeral = 0; 
   var $ed110_i_ptantiguidade = 0; 
   var $ed110_i_ptconvocacao = 0; 
   var $ed110_i_ptavaladmin = 0; 
   var $ed110_i_ptavalpedag = 0; 
   var $ed110_i_ptconhecimento = 0; 
   var $ed110_i_numfaltas = 0; 
   var $ed110_i_numsuspdisc = 0; 
   var $ed110_i_numadvert = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed110_i_codigo = int8 = Código 
                 ed110_i_usuario = int8 = Usuário 
                 ed110_i_inicio = int4 = Tempo de Início 
                 ed110_i_intervalo = int4 = Tempo de Intervalo 
                 ed110_i_ptgeral = int4 = Pontuação Geral no Intervalo 
                 ed110_i_ptantiguidade = int4 = Pontuação da Antiguidade por Ano 
                 ed110_i_ptconvocacao = int4 = Pontuação da Convocação por Ano 
                 ed110_i_ptavaladmin = int4 = Pontuação Avaliação Administrativa por Ano 
                 ed110_i_ptavalpedag = int4 = Pontuação Avaliação Pedagógica por Ano 
                 ed110_i_ptconhecimento = int4 = Pontuação do Conhecimento no Intervalo 
                 ed110_i_numfaltas = int4 = N° de Faltas não justificadas 
                 ed110_i_numsuspdisc = int4 = N° de suspensões disciplinares 
                 ed110_i_numadvert = int4 = N° de penalidades de advertência 
                 ";
   //funcao construtor da classe 
   function cl_progconfig() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progconfig"); 
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
       $this->ed110_i_codigo = ($this->ed110_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_codigo"]:$this->ed110_i_codigo);
       $this->ed110_i_usuario = ($this->ed110_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_usuario"]:$this->ed110_i_usuario);
       $this->ed110_i_inicio = ($this->ed110_i_inicio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_inicio"]:$this->ed110_i_inicio);
       $this->ed110_i_intervalo = ($this->ed110_i_intervalo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_intervalo"]:$this->ed110_i_intervalo);
       $this->ed110_i_ptgeral = ($this->ed110_i_ptgeral == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_ptgeral"]:$this->ed110_i_ptgeral);
       $this->ed110_i_ptantiguidade = ($this->ed110_i_ptantiguidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_ptantiguidade"]:$this->ed110_i_ptantiguidade);
       $this->ed110_i_ptconvocacao = ($this->ed110_i_ptconvocacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_ptconvocacao"]:$this->ed110_i_ptconvocacao);
       $this->ed110_i_ptavaladmin = ($this->ed110_i_ptavaladmin == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_ptavaladmin"]:$this->ed110_i_ptavaladmin);
       $this->ed110_i_ptavalpedag = ($this->ed110_i_ptavalpedag == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_ptavalpedag"]:$this->ed110_i_ptavalpedag);
       $this->ed110_i_ptconhecimento = ($this->ed110_i_ptconhecimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_ptconhecimento"]:$this->ed110_i_ptconhecimento);
       $this->ed110_i_numfaltas = ($this->ed110_i_numfaltas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_numfaltas"]:$this->ed110_i_numfaltas);
       $this->ed110_i_numsuspdisc = ($this->ed110_i_numsuspdisc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_numsuspdisc"]:$this->ed110_i_numsuspdisc);
       $this->ed110_i_numadvert = ($this->ed110_i_numadvert == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_numadvert"]:$this->ed110_i_numadvert);
     }else{
       $this->ed110_i_codigo = ($this->ed110_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_i_codigo"]:$this->ed110_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed110_i_codigo){ 
      $this->atualizacampos();
     if($this->ed110_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed110_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_i_inicio == null ){ 
       $this->erro_sql = " Campo Tempo de Início nao Informado.";
       $this->erro_campo = "ed110_i_inicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_i_intervalo == null ){ 
       $this->erro_sql = " Campo Tempo de Intervalo nao Informado.";
       $this->erro_campo = "ed110_i_intervalo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_i_ptgeral == null ){ 
       $this->ed110_i_ptgeral = "0";
     }
     if($this->ed110_i_ptantiguidade == null ){ 
       $this->ed110_i_ptantiguidade = "0";
     }
     if($this->ed110_i_ptconvocacao == null ){ 
       $this->ed110_i_ptconvocacao = "0";
     }
     if($this->ed110_i_ptavaladmin == null ){ 
       $this->ed110_i_ptavaladmin = "0";
     }
     if($this->ed110_i_ptavalpedag == null ){ 
       $this->ed110_i_ptavalpedag = "0";
     }
     if($this->ed110_i_ptconhecimento == null ){ 
       $this->ed110_i_ptconhecimento = "0";
     }
     if($this->ed110_i_numfaltas == null ){ 
       $this->erro_sql = " Campo N° de Faltas não justificadas nao Informado.";
       $this->erro_campo = "ed110_i_numfaltas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_i_numsuspdisc == null ){ 
       $this->erro_sql = " Campo N° de suspensões disciplinares nao Informado.";
       $this->erro_campo = "ed110_i_numsuspdisc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_i_numadvert == null ){ 
       $this->ed110_i_numadvert = "0";
     }
     if($ed110_i_codigo == "" || $ed110_i_codigo == null ){
       $result = db_query("select nextval('progconfig_ed110_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progconfig_ed110_i_codigo_seq do campo: ed110_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed110_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progconfig_ed110_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed110_i_codigo)){
         $this->erro_sql = " Campo ed110_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed110_i_codigo = $ed110_i_codigo; 
       }
     }
     if(($this->ed110_i_codigo == null) || ($this->ed110_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed110_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progconfig(
                                       ed110_i_codigo 
                                      ,ed110_i_usuario 
                                      ,ed110_i_inicio 
                                      ,ed110_i_intervalo 
                                      ,ed110_i_ptgeral 
                                      ,ed110_i_ptantiguidade 
                                      ,ed110_i_ptconvocacao 
                                      ,ed110_i_ptavaladmin 
                                      ,ed110_i_ptavalpedag 
                                      ,ed110_i_ptconhecimento 
                                      ,ed110_i_numfaltas 
                                      ,ed110_i_numsuspdisc 
                                      ,ed110_i_numadvert 
                       )
                values (
                                $this->ed110_i_codigo 
                               ,$this->ed110_i_usuario 
                               ,$this->ed110_i_inicio 
                               ,$this->ed110_i_intervalo 
                               ,$this->ed110_i_ptgeral 
                               ,$this->ed110_i_ptantiguidade 
                               ,$this->ed110_i_ptconvocacao 
                               ,$this->ed110_i_ptavaladmin 
                               ,$this->ed110_i_ptavalpedag 
                               ,$this->ed110_i_ptconhecimento 
                               ,$this->ed110_i_numfaltas 
                               ,$this->ed110_i_numsuspdisc 
                               ,$this->ed110_i_numadvert 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configurações da Progressão Funcional ($this->ed110_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configurações da Progressão Funcional já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configurações da Progressão Funcional ($this->ed110_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed110_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed110_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009073,'$this->ed110_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010166,1009073,'','".AddSlashes(pg_result($resaco,0,'ed110_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009074,'','".AddSlashes(pg_result($resaco,0,'ed110_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009075,'','".AddSlashes(pg_result($resaco,0,'ed110_i_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009076,'','".AddSlashes(pg_result($resaco,0,'ed110_i_intervalo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009077,'','".AddSlashes(pg_result($resaco,0,'ed110_i_ptgeral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009078,'','".AddSlashes(pg_result($resaco,0,'ed110_i_ptantiguidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009079,'','".AddSlashes(pg_result($resaco,0,'ed110_i_ptconvocacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009080,'','".AddSlashes(pg_result($resaco,0,'ed110_i_ptavaladmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009081,'','".AddSlashes(pg_result($resaco,0,'ed110_i_ptavalpedag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009082,'','".AddSlashes(pg_result($resaco,0,'ed110_i_ptconhecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009083,'','".AddSlashes(pg_result($resaco,0,'ed110_i_numfaltas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009084,'','".AddSlashes(pg_result($resaco,0,'ed110_i_numsuspdisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010166,1009085,'','".AddSlashes(pg_result($resaco,0,'ed110_i_numadvert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed110_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update progconfig set ";
     $virgula = "";
     if(trim($this->ed110_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_codigo"])){ 
       $sql  .= $virgula." ed110_i_codigo = $this->ed110_i_codigo ";
       $virgula = ",";
       if(trim($this->ed110_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed110_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_usuario"])){ 
       $sql  .= $virgula." ed110_i_usuario = $this->ed110_i_usuario ";
       $virgula = ",";
       if(trim($this->ed110_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed110_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_i_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_inicio"])){ 
       $sql  .= $virgula." ed110_i_inicio = $this->ed110_i_inicio ";
       $virgula = ",";
       if(trim($this->ed110_i_inicio) == null ){ 
         $this->erro_sql = " Campo Tempo de Início nao Informado.";
         $this->erro_campo = "ed110_i_inicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_i_intervalo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_intervalo"])){ 
       $sql  .= $virgula." ed110_i_intervalo = $this->ed110_i_intervalo ";
       $virgula = ",";
       if(trim($this->ed110_i_intervalo) == null ){ 
         $this->erro_sql = " Campo Tempo de Intervalo nao Informado.";
         $this->erro_campo = "ed110_i_intervalo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_i_ptgeral)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptgeral"])){ 
        if(trim($this->ed110_i_ptgeral)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptgeral"])){ 
           $this->ed110_i_ptgeral = "0" ; 
        } 
       $sql  .= $virgula." ed110_i_ptgeral = $this->ed110_i_ptgeral ";
       $virgula = ",";
     }
     if(trim($this->ed110_i_ptantiguidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptantiguidade"])){ 
        if(trim($this->ed110_i_ptantiguidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptantiguidade"])){ 
           $this->ed110_i_ptantiguidade = "0" ; 
        } 
       $sql  .= $virgula." ed110_i_ptantiguidade = $this->ed110_i_ptantiguidade ";
       $virgula = ",";
     }
     if(trim($this->ed110_i_ptconvocacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptconvocacao"])){ 
        if(trim($this->ed110_i_ptconvocacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptconvocacao"])){ 
           $this->ed110_i_ptconvocacao = "0" ; 
        } 
       $sql  .= $virgula." ed110_i_ptconvocacao = $this->ed110_i_ptconvocacao ";
       $virgula = ",";
     }
     if(trim($this->ed110_i_ptavaladmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptavaladmin"])){ 
        if(trim($this->ed110_i_ptavaladmin)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptavaladmin"])){ 
           $this->ed110_i_ptavaladmin = "0" ; 
        } 
       $sql  .= $virgula." ed110_i_ptavaladmin = $this->ed110_i_ptavaladmin ";
       $virgula = ",";
     }
     if(trim($this->ed110_i_ptavalpedag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptavalpedag"])){ 
        if(trim($this->ed110_i_ptavalpedag)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptavalpedag"])){ 
           $this->ed110_i_ptavalpedag = "0" ; 
        } 
       $sql  .= $virgula." ed110_i_ptavalpedag = $this->ed110_i_ptavalpedag ";
       $virgula = ",";
     }
     if(trim($this->ed110_i_ptconhecimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptconhecimento"])){ 
        if(trim($this->ed110_i_ptconhecimento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptconhecimento"])){ 
           $this->ed110_i_ptconhecimento = "0" ; 
        } 
       $sql  .= $virgula." ed110_i_ptconhecimento = $this->ed110_i_ptconhecimento ";
       $virgula = ",";
     }
     if(trim($this->ed110_i_numfaltas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_numfaltas"])){ 
       $sql  .= $virgula." ed110_i_numfaltas = $this->ed110_i_numfaltas ";
       $virgula = ",";
       if(trim($this->ed110_i_numfaltas) == null ){ 
         $this->erro_sql = " Campo N° de Faltas não justificadas nao Informado.";
         $this->erro_campo = "ed110_i_numfaltas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_i_numsuspdisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_numsuspdisc"])){ 
       $sql  .= $virgula." ed110_i_numsuspdisc = $this->ed110_i_numsuspdisc ";
       $virgula = ",";
       if(trim($this->ed110_i_numsuspdisc) == null ){ 
         $this->erro_sql = " Campo N° de suspensões disciplinares nao Informado.";
         $this->erro_campo = "ed110_i_numsuspdisc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_i_numadvert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_numadvert"])){ 
        if(trim($this->ed110_i_numadvert)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_numadvert"])){ 
           $this->ed110_i_numadvert = "0" ; 
        } 
       $sql  .= $virgula." ed110_i_numadvert = $this->ed110_i_numadvert ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed110_i_codigo!=null){
       $sql .= " ed110_i_codigo = $this->ed110_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed110_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009073,'$this->ed110_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009073,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_codigo'))."','$this->ed110_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009074,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_usuario'))."','$this->ed110_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_inicio"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009075,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_inicio'))."','$this->ed110_i_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_intervalo"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009076,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_intervalo'))."','$this->ed110_i_intervalo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptgeral"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009077,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_ptgeral'))."','$this->ed110_i_ptgeral',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptantiguidade"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009078,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_ptantiguidade'))."','$this->ed110_i_ptantiguidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptconvocacao"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009079,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_ptconvocacao'))."','$this->ed110_i_ptconvocacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptavaladmin"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009080,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_ptavaladmin'))."','$this->ed110_i_ptavaladmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptavalpedag"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009081,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_ptavalpedag'))."','$this->ed110_i_ptavalpedag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_ptconhecimento"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009082,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_ptconhecimento'))."','$this->ed110_i_ptconhecimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_numfaltas"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009083,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_numfaltas'))."','$this->ed110_i_numfaltas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_numsuspdisc"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009084,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_numsuspdisc'))."','$this->ed110_i_numsuspdisc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_i_numadvert"]))
           $resac = db_query("insert into db_acount values($acount,1010166,1009085,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_i_numadvert'))."','$this->ed110_i_numadvert',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configurações da Progressão Funcional nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed110_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configurações da Progressão Funcional nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed110_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed110_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed110_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed110_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009073,'$ed110_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010166,1009073,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009074,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009075,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009076,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_intervalo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009077,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_ptgeral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009078,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_ptantiguidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009079,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_ptconvocacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009080,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_ptavaladmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009081,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_ptavalpedag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009082,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_ptconhecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009083,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_numfaltas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009084,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_numsuspdisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010166,1009085,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_i_numadvert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progconfig
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed110_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed110_i_codigo = $ed110_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configurações da Progressão Funcional nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed110_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configurações da Progressão Funcional nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed110_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed110_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:progconfig";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed110_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progconfig ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = progconfig.ed110_i_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed110_i_codigo!=null ){
         $sql2 .= " where progconfig.ed110_i_codigo = $ed110_i_codigo "; 
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
   function sql_query_file ( $ed110_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progconfig ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed110_i_codigo!=null ){
         $sql2 .= " where progconfig.ed110_i_codigo = $ed110_i_codigo "; 
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