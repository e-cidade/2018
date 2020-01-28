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

//MODULO: escola
//CLASSE DA ENTIDADE histmpsdisc
class cl_histmpsdisc { 
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
   var $ed65_i_codigo = 0; 
   var $ed65_i_historicomps = 0; 
   var $ed65_i_disciplina = 0; 
   var $ed65_i_justificativa = 0;
   var $ed65_i_qtdch = 0; 
   var $ed65_c_resultadofinal = null; 
   var $ed65_t_resultobtido = null; 
   var $ed65_c_situacao = null; 
   var $ed65_c_tiporesultado = null; 
   var $ed65_i_ordenacao = 0; 
   var $ed65_c_termofinal = null; 
   var $ed65_lancamentoautomatico = 'f'; 
   var $ed65_opcional = 'f'; 
   var $ed65_basecomum = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed65_i_codigo = int8 = Código 
                 ed65_i_historicomps = int8 = Histórico 
                 ed65_i_disciplina = int8 = Disciplina 
                 ed65_i_justificativa = int8 = Justificativa 
                 ed65_i_qtdch = numeric(10) = Carga Horária 
                 ed65_c_resultadofinal = char(1) = Resultado Final 
                 ed65_t_resultobtido = text = Aproveitamento 
                 ed65_c_situacao = char(20) = Situação 
                 ed65_c_tiporesultado = char(1) = Tipo de Resultado 
                 ed65_i_ordenacao = int4 = Ordenar Disciplina 
                 ed65_c_termofinal = varchar(4) = Termo Final 
                 ed65_lancamentoautomatico = bool = Lançamento Automático 
                 ed65_opcional = bool = Opcional 
                 ed65_basecomum = bool = Base Comum 
                 ";
   //funcao construtor da classe 
   function cl_histmpsdisc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("histmpsdisc"); 
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
       $this->ed65_i_codigo = ($this->ed65_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_i_codigo"]:$this->ed65_i_codigo);
       $this->ed65_i_historicomps = ($this->ed65_i_historicomps == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_i_historicomps"]:$this->ed65_i_historicomps);
       $this->ed65_i_disciplina = ($this->ed65_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_i_disciplina"]:$this->ed65_i_disciplina);
       $this->ed65_i_justificativa = ($this->ed65_i_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_i_justificativa"]:$this->ed65_i_justificativa);
       $this->ed65_i_qtdch = ($this->ed65_i_qtdch == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_i_qtdch"]:$this->ed65_i_qtdch);
       $this->ed65_c_resultadofinal = ($this->ed65_c_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_c_resultadofinal"]:$this->ed65_c_resultadofinal);
       $this->ed65_t_resultobtido = ($this->ed65_t_resultobtido == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_t_resultobtido"]:$this->ed65_t_resultobtido);
       $this->ed65_c_situacao = ($this->ed65_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_c_situacao"]:$this->ed65_c_situacao);
       $this->ed65_c_tiporesultado = ($this->ed65_c_tiporesultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_c_tiporesultado"]:$this->ed65_c_tiporesultado);
       $this->ed65_i_ordenacao = ($this->ed65_i_ordenacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_i_ordenacao"]:$this->ed65_i_ordenacao);
       $this->ed65_c_termofinal = ($this->ed65_c_termofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_c_termofinal"]:$this->ed65_c_termofinal);
       $this->ed65_lancamentoautomatico = ($this->ed65_lancamentoautomatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed65_lancamentoautomatico"]:$this->ed65_lancamentoautomatico);
       $this->ed65_opcional = ($this->ed65_opcional == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_opcional"]:$this->ed65_opcional);
       $this->ed65_basecomum = ($this->ed65_basecomum == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed65_basecomum"]:$this->ed65_basecomum);
     }else{
       $this->ed65_i_codigo = ($this->ed65_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed65_i_codigo"]:$this->ed65_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed65_i_codigo){ 
      $this->atualizacampos();
     if($this->ed65_i_historicomps == null ){ 
       $this->erro_sql = " Campo Histórico não informado.";
       $this->erro_campo = "ed65_i_historicomps";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed65_i_disciplina == null ){ 
       $this->erro_sql = " Campo Disciplina não informado.";
       $this->erro_campo = "ed65_i_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed65_i_justificativa == null ){ 
       $this->ed65_i_justificativa = "null";
     }
     if($this->ed65_i_qtdch == null ){ 
       $this->ed65_i_qtdch = "null";
     }
     if($this->ed65_i_ordenacao == null ){ 
       $this->ed65_i_ordenacao = "0";
     }
     if($this->ed65_lancamentoautomatico == null ){ 
       $this->erro_sql = " Campo Lançamento Automático não informado.";
       $this->erro_campo = "ed65_lancamentoautomatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed65_opcional == null ){ 
       $this->erro_sql = " Campo Opcional não informado.";
       $this->erro_campo = "ed65_opcional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed65_basecomum == null ){ 
       $this->erro_sql = " Campo Base Comum não informado.";
       $this->erro_campo = "ed65_basecomum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed65_i_codigo == "" || $ed65_i_codigo == null ){
       $result = db_query("select nextval('histmpsdisc_ed65_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: histmpsdisc_ed65_i_codigo_seq do campo: ed65_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed65_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from histmpsdisc_ed65_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed65_i_codigo)){
         $this->erro_sql = " Campo ed65_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed65_i_codigo = $ed65_i_codigo; 
       }
     }
     if(($this->ed65_i_codigo == null) || ($this->ed65_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed65_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into histmpsdisc(
                                       ed65_i_codigo 
                                      ,ed65_i_historicomps 
                                      ,ed65_i_disciplina 
                                      ,ed65_i_justificativa 
                                      ,ed65_i_qtdch 
                                      ,ed65_c_resultadofinal 
                                      ,ed65_t_resultobtido 
                                      ,ed65_c_situacao 
                                      ,ed65_c_tiporesultado 
                                      ,ed65_i_ordenacao 
                                      ,ed65_c_termofinal 
                                      ,ed65_lancamentoautomatico 
                                      ,ed65_opcional 
                                      ,ed65_basecomum 
                       )
                values (
                                $this->ed65_i_codigo 
                               ,$this->ed65_i_historicomps 
                               ,$this->ed65_i_disciplina 
                               ,$this->ed65_i_justificativa 
                               ,$this->ed65_i_qtdch 
                               ,'$this->ed65_c_resultadofinal' 
                               ,'$this->ed65_t_resultobtido' 
                               ,'$this->ed65_c_situacao' 
                               ,'$this->ed65_c_tiporesultado' 
                               ,$this->ed65_i_ordenacao 
                               ,'$this->ed65_c_termofinal' 
                               ,'$this->ed65_lancamentoautomatico' 
                               ,'$this->ed65_opcional' 
                               ,'$this->ed65_basecomum' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Disciplina do Historico MPS ($this->ed65_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Disciplina do Historico MPS já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Disciplina do Historico MPS ($this->ed65_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed65_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed65_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008779,'$this->ed65_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010133,1008779,'','".AddSlashes(pg_result($resaco,0,'ed65_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,1008780,'','".AddSlashes(pg_result($resaco,0,'ed65_i_historicomps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,1008781,'','".AddSlashes(pg_result($resaco,0,'ed65_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,1008782,'','".AddSlashes(pg_result($resaco,0,'ed65_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,1008783,'','".AddSlashes(pg_result($resaco,0,'ed65_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,1008784,'','".AddSlashes(pg_result($resaco,0,'ed65_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,1008785,'','".AddSlashes(pg_result($resaco,0,'ed65_t_resultobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,1008786,'','".AddSlashes(pg_result($resaco,0,'ed65_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,1008787,'','".AddSlashes(pg_result($resaco,0,'ed65_c_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,14823,'','".AddSlashes(pg_result($resaco,0,'ed65_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,19694,'','".AddSlashes(pg_result($resaco,0,'ed65_c_termofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,19792,'','".AddSlashes(pg_result($resaco,0,'ed65_lancamentoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,20322,'','".AddSlashes(pg_result($resaco,0,'ed65_opcional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010133,20663,'','".AddSlashes(pg_result($resaco,0,'ed65_basecomum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed65_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update histmpsdisc set ";
     $virgula = "";
     if(trim($this->ed65_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_codigo"])){ 
       $sql  .= $virgula." ed65_i_codigo = $this->ed65_i_codigo ";
       $virgula = ",";
       if(trim($this->ed65_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed65_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed65_i_historicomps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_historicomps"])){ 
       $sql  .= $virgula." ed65_i_historicomps = $this->ed65_i_historicomps ";
       $virgula = ",";
       if(trim($this->ed65_i_historicomps) == null ){ 
         $this->erro_sql = " Campo Histórico não informado.";
         $this->erro_campo = "ed65_i_historicomps";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed65_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_disciplina"])){ 
       $sql  .= $virgula." ed65_i_disciplina = $this->ed65_i_disciplina ";
       $virgula = ",";
       if(trim($this->ed65_i_disciplina) == null ){ 
         $this->erro_sql = " Campo Disciplina não informado.";
         $this->erro_campo = "ed65_i_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed65_i_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_justificativa"])){ 
        if(trim($this->ed65_i_justificativa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_justificativa"])){ 
           $this->ed65_i_justificativa = "0" ; 
        } 
       $sql  .= $virgula." ed65_i_justificativa = $this->ed65_i_justificativa ";
       $virgula = ",";
     }
     if(trim($this->ed65_i_qtdch)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_qtdch"])){ 
       if(trim($this->ed65_i_qtdch)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_qtdch"])){
         $this->ed65_i_qtdch = "0" ;
       }
       $sql  .= $virgula." ed65_i_qtdch = $this->ed65_i_qtdch ";
       $virgula = ",";
     }
     if (   $this->ed65_c_resultadofinal == null 
         || trim($this->ed65_c_resultadofinal) != "" 
         || isset($GLOBALS["HTTP_POST_VARS"]["ed65_c_resultadofinal"])) { 
       $sql  .= $virgula." ed65_c_resultadofinal = '$this->ed65_c_resultadofinal' ";
       $virgula = ",";
     }
     if (   $this->ed65_t_resultobtido == null 
         || trim($this->ed65_t_resultobtido) != "" 
         || isset($GLOBALS["HTTP_POST_VARS"]["ed65_t_resultobtido"])) { 
       $sql  .= $virgula." ed65_t_resultobtido = '$this->ed65_t_resultobtido' ";
       $virgula = ",";
     }
     if(trim($this->ed65_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_c_situacao"])){ 
       $sql  .= $virgula." ed65_c_situacao = '$this->ed65_c_situacao' ";
       $virgula = ",";
     }
     if(trim($this->ed65_c_tiporesultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_c_tiporesultado"])){ 
       $sql  .= $virgula." ed65_c_tiporesultado = '$this->ed65_c_tiporesultado' ";
       $virgula = ",";
     }
     if(trim($this->ed65_i_ordenacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_ordenacao"])){ 
        if(trim($this->ed65_i_ordenacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_ordenacao"])){ 
           $this->ed65_i_ordenacao = "0" ; 
        } 
       $sql  .= $virgula." ed65_i_ordenacao = $this->ed65_i_ordenacao ";
       $virgula = ",";
     }
     if (   $this->ed65_c_termofinal == null 
         || trim($this->ed65_c_termofinal) != "" 
         || isset($GLOBALS["HTTP_POST_VARS"]["ed65_c_termofinal"])) { 
       $sql  .= $virgula." ed65_c_termofinal = '$this->ed65_c_termofinal' ";
       $virgula = ",";
     }
     if(trim($this->ed65_lancamentoautomatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_lancamentoautomatico"])){ 
       $sql  .= $virgula." ed65_lancamentoautomatico = '$this->ed65_lancamentoautomatico' ";
       $virgula = ",";
       if(trim($this->ed65_lancamentoautomatico) == null ){ 
         $this->erro_sql = " Campo Lançamento Automático não informado.";
         $this->erro_campo = "ed65_lancamentoautomatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed65_opcional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_opcional"])){ 
       $sql  .= $virgula." ed65_opcional = '$this->ed65_opcional' ";
       $virgula = ",";
       if(trim($this->ed65_opcional) == null ){ 
         $this->erro_sql = " Campo Opcional não informado.";
         $this->erro_campo = "ed65_opcional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed65_basecomum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed65_basecomum"])){ 
       $sql  .= $virgula." ed65_basecomum = '$this->ed65_basecomum' ";
       $virgula = ",";
       if(trim($this->ed65_basecomum) == null ){ 
         $this->erro_sql = " Campo Base Comum não informado.";
         $this->erro_campo = "ed65_basecomum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed65_i_codigo!=null){
       $sql .= " ed65_i_codigo = $this->ed65_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed65_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008779,'$this->ed65_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_codigo"]) || $this->ed65_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008779,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_i_codigo'))."','$this->ed65_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_historicomps"]) || $this->ed65_i_historicomps != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008780,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_i_historicomps'))."','$this->ed65_i_historicomps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_disciplina"]) || $this->ed65_i_disciplina != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008781,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_i_disciplina'))."','$this->ed65_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_justificativa"]) || $this->ed65_i_justificativa != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008782,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_i_justificativa'))."','$this->ed65_i_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_qtdch"]) || $this->ed65_i_qtdch != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008783,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_i_qtdch'))."','$this->ed65_i_qtdch',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_c_resultadofinal"]) || $this->ed65_c_resultadofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008784,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_c_resultadofinal'))."','$this->ed65_c_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_t_resultobtido"]) || $this->ed65_t_resultobtido != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008785,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_t_resultobtido'))."','$this->ed65_t_resultobtido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_c_situacao"]) || $this->ed65_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008786,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_c_situacao'))."','$this->ed65_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_c_tiporesultado"]) || $this->ed65_c_tiporesultado != "")
             $resac = db_query("insert into db_acount values($acount,1010133,1008787,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_c_tiporesultado'))."','$this->ed65_c_tiporesultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_i_ordenacao"]) || $this->ed65_i_ordenacao != "")
             $resac = db_query("insert into db_acount values($acount,1010133,14823,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_i_ordenacao'))."','$this->ed65_i_ordenacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_c_termofinal"]) || $this->ed65_c_termofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010133,19694,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_c_termofinal'))."','$this->ed65_c_termofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_lancamentoautomatico"]) || $this->ed65_lancamentoautomatico != "")
             $resac = db_query("insert into db_acount values($acount,1010133,19792,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_lancamentoautomatico'))."','$this->ed65_lancamentoautomatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_opcional"]) || $this->ed65_opcional != "")
             $resac = db_query("insert into db_acount values($acount,1010133,20322,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_opcional'))."','$this->ed65_opcional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed65_basecomum"]) || $this->ed65_basecomum != "")
             $resac = db_query("insert into db_acount values($acount,1010133,20663,'".AddSlashes(pg_result($resaco,$conresaco,'ed65_basecomum'))."','$this->ed65_basecomum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplina do Historico MPS nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed65_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplina do Historico MPS nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed65_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed65_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed65_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed65_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008779,'$ed65_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008779,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008780,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_i_historicomps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008781,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008782,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008783,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008784,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008785,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_t_resultobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008786,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,1008787,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_c_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,14823,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,19694,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_c_termofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,19792,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_lancamentoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,20322,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_opcional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010133,20663,'','".AddSlashes(pg_result($resaco,$iresaco,'ed65_basecomum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from histmpsdisc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed65_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed65_i_codigo = $ed65_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplina do Historico MPS nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed65_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplina do Historico MPS nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed65_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed65_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:histmpsdisc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed65_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histmpsdisc ";
     $sql .= "      left join justificativa  on  justificativa.ed06_i_codigo = histmpsdisc.ed65_i_justificativa";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = histmpsdisc.ed65_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join historicomps  on  historicomps.ed62_i_codigo = histmpsdisc.ed65_i_historicomps";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = historicomps.ed62_i_escola";
     $sql .= "      left join justificativa as a on  a.ed06_i_codigo = historicomps.ed62_i_justificativa";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = historicomps.ed62_i_serie";
     $sql .= "      inner join historico   on  historico.ed61_i_codigo = historicomps.ed62_i_historico";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = historico.ed61_i_aluno";
     $sql .= "      inner join cursoedu   on  cursoedu.ed29_i_codigo = historico.ed61_i_curso";
     $sql2 = "";
     if($dbwhere==""){
       if($ed65_i_codigo!=null ){
         $sql2 .= " where histmpsdisc.ed65_i_codigo = $ed65_i_codigo "; 
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
   function sql_query_file ( $ed65_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histmpsdisc ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed65_i_codigo!=null ){
         $sql2 .= " where histmpsdisc.ed65_i_codigo = $ed65_i_codigo "; 
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
   function sql_query_certconclusao($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
       $sSql .= $sCampos;
    }
    
    $sSql .= " from histmpsdisc " ;
    $sSql .= " inner join disciplina on ed12_i_codigo = ed65_i_disciplina ";
    $sSql .= " inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
    $sSql .= " inner join historicomps on ed62_i_codigo = ed65_i_historicomps ";
    $sSql .= " inner join serie on ed11_i_codigo = ed62_i_serie ";
    $sSql .= " inner join historico on ed61_i_codigo = ed62_i_historico ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where histmpsdisc.ed65_i_codigo  = $iCodigo ";
      }

    } else if ($sDbWhere != '') {
       $sSql2 = " where $sDbWhere";
    } 
    $sSql .= $sSql2;
    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
   function sql_query_histmpsdisc($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from histmpsdisc " ;       
    $sSql .= "      left join justificativa  on  justificativa.ed06_i_codigo = histmpsdisc.ed65_i_justificativa ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where histmpsdisc.ed65_i_codigo  = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }        
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
   /**
   * Busca os dados do certificado de comclusao
   * @param integer $iCodigo
   * @param string $sCampos
   * @param string $sOrdem
   * @param string $sDbWhere
   */
  function sql_query_certificado_conclusao($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {
  
  	$sSql = 'select ';
  	if ($sCampos != '*') {
  
  		$sCamposSql = split('#', $sCampos);
  		$sVirgula   = '';
  		for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){
  
  			$sSql .= $sVirgula.$sCamposSql[$iCont];
  			$virgula = ",";
  
  		}
  
  	} else {
  		$sSql .= $sCampos;
  	}
  
  	$sSql .= " from histmpsdisc " ;
  	$sSql .= " inner join disciplina on ed12_i_codigo = ed65_i_disciplina       ";
  	$sSql .= " inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
  	$sSql .= " inner join historicomps on ed62_i_codigo = ed65_i_historicomps   ";
  	$sSql .= " inner join serie on ed11_i_codigo = ed62_i_serie                 ";
  	$sSql .= " inner join historico on ed61_i_codigo = ed62_i_historico         ";
  	$sSql .= " inner join cursoedu on ed29_i_codigo = ed61_i_curso              ";
  	$sSql2 = '';
  	if ($sDbWhere == '') {
  
  		if ($iCodigo != null ){
  			$sSql2 .= " where histmpsdisc.ed65_i_codigo  = $iCodigo ";
  		}
  
  	} else if ($sDbWhere != '') {
  		$sSql2 = " where $sDbWhere";
  	}
  	$sSql .= $sSql2;
  	if ($sOrdem != null) {
  
  		$sSql      .= ' order by ';
  		$sCamposSql = split('#', $sOrdem);
  		$sVirgula   = '';
  		for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {
  
  			$sSql    .= $sVirgula.$sCamposSql[$iCont];
  			$sVirgula = ',';
  
  		}
  
  	}
  
  	return $sSql;
  
  }
   function sql_query_historico ( $ed65_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from histmpsdisc                                                                                \n";
    $sql .= "      left  join justificativa on justificativa.ed06_i_codigo = histmpsdisc.ed65_i_justificativa \n";
    $sql .= "      inner join disciplina    on disciplina.ed12_i_codigo = histmpsdisc.ed65_i_disciplina       \n";
    $sql .= "      inner join historicomps  on historicomps.ed62_i_codigo = histmpsdisc.ed65_i_historicomps   \n";
    $sql .= "      inner join escola        on escola.ed18_i_codigo = historicomps.ed62_i_escola              \n";
    $sql .= "      inner join caddisciplina on caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina \n";
    $sql .= "      inner join ensino        on ensino.ed10_i_codigo = disciplina.ed12_i_ensino                \n";
    $sql .= "      inner join serie         on serie.ed11_i_codigo = historicomps.ed62_i_serie                \n";
    $sql .= "      inner join historico     on historico.ed61_i_codigo = historicomps.ed62_i_historico        \n";
    $sql .= "      inner join cursoedu      on cursoedu.ed29_i_codigo = historico.ed61_i_curso                  ";
    $sql2 = "";
    
    if ($dbwhere == "") {
      
      if ($ed65_i_codigo != null ) {
        $sql2 .= " where histmpsdisc.ed65_i_codigo = $ed65_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
?>
