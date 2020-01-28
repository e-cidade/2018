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
//CLASSE DA ENTIDADE histmpsdiscfora
class cl_histmpsdiscfora { 
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
   var $ed100_i_codigo = 0; 
   var $ed100_i_historicompsfora = 0; 
   var $ed100_i_disciplina = 0; 
   var $ed100_i_justificativa = 0; 
   var $ed100_i_qtdch = 0; 
   var $ed100_c_resultadofinal = null; 
   var $ed100_t_resultobtido = null; 
   var $ed100_c_situacao = null; 
   var $ed100_c_tiporesultado = null; 
   var $ed100_i_ordenacao = 0; 
   var $ed100_c_termofinal = null; 
   var $ed100_opcional = 'f'; 
   var $ed100_basecomum = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed100_i_codigo = int8 = Código 
                 ed100_i_historicompsfora = int8 = Histórico 
                 ed100_i_disciplina = int8 = Disciplina 
                 ed100_i_justificativa = int8 = Justificativa 
                 ed100_i_qtdch = numeric(10) = Carga Horária 
                 ed100_c_resultadofinal = char(1) = Resultado Final 
                 ed100_t_resultobtido = text = Aproveitamento 
                 ed100_c_situacao = char(20) = Situação 
                 ed100_c_tiporesultado = char(1) = Tipo de Resultado 
                 ed100_i_ordenacao = int4 = Ordenar Disciplina 
                 ed100_c_termofinal = varchar(4) = Termo Final 
                 ed100_opcional = bool = Opcional 
                 ed100_basecomum = bool = Base Comum 
                 ";
   //funcao construtor da classe 
   function cl_histmpsdiscfora() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("histmpsdiscfora"); 
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
       $this->ed100_i_codigo = ($this->ed100_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_i_codigo"]:$this->ed100_i_codigo);
       $this->ed100_i_historicompsfora = ($this->ed100_i_historicompsfora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_i_historicompsfora"]:$this->ed100_i_historicompsfora);
       $this->ed100_i_disciplina = ($this->ed100_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_i_disciplina"]:$this->ed100_i_disciplina);
       $this->ed100_i_justificativa = ($this->ed100_i_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_i_justificativa"]:$this->ed100_i_justificativa);
       $this->ed100_i_qtdch = ($this->ed100_i_qtdch == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_i_qtdch"]:$this->ed100_i_qtdch);
       $this->ed100_c_resultadofinal = ($this->ed100_c_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_c_resultadofinal"]:$this->ed100_c_resultadofinal);
       $this->ed100_t_resultobtido = ($this->ed100_t_resultobtido == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_t_resultobtido"]:$this->ed100_t_resultobtido);
       $this->ed100_c_situacao = ($this->ed100_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_c_situacao"]:$this->ed100_c_situacao);
       $this->ed100_c_tiporesultado = ($this->ed100_c_tiporesultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_c_tiporesultado"]:$this->ed100_c_tiporesultado);
       $this->ed100_i_ordenacao = ($this->ed100_i_ordenacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_i_ordenacao"]:$this->ed100_i_ordenacao);
       $this->ed100_c_termofinal = ($this->ed100_c_termofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_c_termofinal"]:$this->ed100_c_termofinal);
       $this->ed100_opcional = ($this->ed100_opcional == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed100_opcional"]:$this->ed100_opcional);
       $this->ed100_basecomum = ($this->ed100_basecomum == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed100_basecomum"]:$this->ed100_basecomum);
     }else{
       $this->ed100_i_codigo = ($this->ed100_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_i_codigo"]:$this->ed100_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed100_i_codigo){ 
      $this->atualizacampos();
     if($this->ed100_i_historicompsfora == null ){ 
       $this->erro_sql = " Campo Histórico não informado.";
       $this->erro_campo = "ed100_i_historicompsfora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed100_i_disciplina == null ){ 
       $this->erro_sql = " Campo Disciplina não informado.";
       $this->erro_campo = "ed100_i_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed100_i_justificativa == null ){ 
       $this->ed100_i_justificativa = "null";
     }
     if($this->ed100_i_qtdch == null ){ 
       $this->ed100_i_qtdch = "null";
     }
     if($this->ed100_i_ordenacao == null ){ 
       $this->ed100_i_ordenacao = "0";
     }
     if($this->ed100_opcional == null ){ 
       $this->erro_sql = " Campo Opcional não informado.";
       $this->erro_campo = "ed100_opcional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed100_basecomum == null ){ 
       $this->erro_sql = " Campo Base Comum não informado.";
       $this->erro_campo = "ed100_basecomum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed100_i_codigo == "" || $ed100_i_codigo == null ){
       $result = db_query("select nextval('histmpsdiscfora_ed100_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: histmpsdiscfora_ed100_i_codigo_seq do campo: ed100_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed100_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from histmpsdiscfora_ed100_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed100_i_codigo)){
         $this->erro_sql = " Campo ed100_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed100_i_codigo = $ed100_i_codigo; 
       }
     }
     if(($this->ed100_i_codigo == null) || ($this->ed100_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed100_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into histmpsdiscfora(
                                       ed100_i_codigo 
                                      ,ed100_i_historicompsfora 
                                      ,ed100_i_disciplina 
                                      ,ed100_i_justificativa 
                                      ,ed100_i_qtdch 
                                      ,ed100_c_resultadofinal 
                                      ,ed100_t_resultobtido 
                                      ,ed100_c_situacao 
                                      ,ed100_c_tiporesultado 
                                      ,ed100_i_ordenacao 
                                      ,ed100_c_termofinal 
                                      ,ed100_opcional 
                                      ,ed100_basecomum 
                       )
                values (
                                $this->ed100_i_codigo 
                               ,$this->ed100_i_historicompsfora 
                               ,$this->ed100_i_disciplina 
                               ,$this->ed100_i_justificativa 
                               ,$this->ed100_i_qtdch 
                               ,'$this->ed100_c_resultadofinal' 
                               ,'$this->ed100_t_resultobtido' 
                               ,'$this->ed100_c_situacao' 
                               ,'$this->ed100_c_tiporesultado' 
                               ,$this->ed100_i_ordenacao 
                               ,'$this->ed100_c_termofinal' 
                               ,'$this->ed100_opcional' 
                               ,'$this->ed100_basecomum' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das Disciplinas do Historico ($this->ed100_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das Disciplinas do Historico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das Disciplinas do Historico ($this->ed100_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed100_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed100_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009020,'$this->ed100_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010159,1009020,'','".AddSlashes(pg_result($resaco,0,'ed100_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,1009021,'','".AddSlashes(pg_result($resaco,0,'ed100_i_historicompsfora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,1009022,'','".AddSlashes(pg_result($resaco,0,'ed100_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,1009023,'','".AddSlashes(pg_result($resaco,0,'ed100_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,1009024,'','".AddSlashes(pg_result($resaco,0,'ed100_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,1009025,'','".AddSlashes(pg_result($resaco,0,'ed100_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,1009026,'','".AddSlashes(pg_result($resaco,0,'ed100_t_resultobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,1009027,'','".AddSlashes(pg_result($resaco,0,'ed100_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,1009028,'','".AddSlashes(pg_result($resaco,0,'ed100_c_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,14824,'','".AddSlashes(pg_result($resaco,0,'ed100_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,19695,'','".AddSlashes(pg_result($resaco,0,'ed100_c_termofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,20323,'','".AddSlashes(pg_result($resaco,0,'ed100_opcional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010159,20664,'','".AddSlashes(pg_result($resaco,0,'ed100_basecomum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed100_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update histmpsdiscfora set ";
     $virgula = "";
     if(trim($this->ed100_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_codigo"])){ 
       $sql  .= $virgula." ed100_i_codigo = $this->ed100_i_codigo ";
       $virgula = ",";
       if(trim($this->ed100_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed100_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed100_i_historicompsfora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_historicompsfora"])){ 
       $sql  .= $virgula." ed100_i_historicompsfora = $this->ed100_i_historicompsfora ";
       $virgula = ",";
       if(trim($this->ed100_i_historicompsfora) == null ){ 
         $this->erro_sql = " Campo Histórico não informado.";
         $this->erro_campo = "ed100_i_historicompsfora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed100_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_disciplina"])){ 
       $sql  .= $virgula." ed100_i_disciplina = $this->ed100_i_disciplina ";
       $virgula = ",";
       if(trim($this->ed100_i_disciplina) == null ){ 
         $this->erro_sql = " Campo Disciplina não informado.";
         $this->erro_campo = "ed100_i_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed100_i_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_justificativa"])){ 
        if(trim($this->ed100_i_justificativa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_justificativa"])){ 
           $this->ed100_i_justificativa = "0" ; 
        } 
       $sql  .= $virgula." ed100_i_justificativa = $this->ed100_i_justificativa ";
       $virgula = ",";
     }
     if(trim($this->ed100_i_qtdch)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_qtdch"])){ 
       if(trim($this->ed100_i_qtdch)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_qtdch"])){
         $this->ed100_i_qtdch = "0" ;
       }
       $sql  .= $virgula." ed100_i_qtdch = $this->ed100_i_qtdch ";
       $virgula = ",";
     }
     if (   $this->ed100_c_resultadofinal == null
         || trim($this->ed100_c_resultadofinal) != ""
         || isset($GLOBALS["HTTP_POST_VARS"]["ed100_c_resultadofinal"])) { 
       $sql  .= $virgula." ed100_c_resultadofinal = '$this->ed100_c_resultadofinal' ";
       $virgula = ",";
     }
     if (   $this->ed100_t_resultobtido == null
         || trim($this->ed100_t_resultobtido) != ""
         || isset($GLOBALS["HTTP_POST_VARS"]["ed100_t_resultobtido"])) { 
       $sql  .= $virgula." ed100_t_resultobtido = '$this->ed100_t_resultobtido' ";
       $virgula = ",";
     }
     if(trim($this->ed100_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_c_situacao"])){ 
       $sql  .= $virgula." ed100_c_situacao = '$this->ed100_c_situacao' ";
       $virgula = ",";
     }
     if(trim($this->ed100_c_tiporesultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_c_tiporesultado"])){ 
       $sql  .= $virgula." ed100_c_tiporesultado = '$this->ed100_c_tiporesultado' ";
       $virgula = ",";
     }
     if(trim($this->ed100_i_ordenacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_ordenacao"])){ 
        if(trim($this->ed100_i_ordenacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_ordenacao"])){ 
           $this->ed100_i_ordenacao = "0" ; 
        } 
       $sql  .= $virgula." ed100_i_ordenacao = $this->ed100_i_ordenacao ";
       $virgula = ",";
     }
     if (   $this->ed100_c_termofinal == null
         || trim($this->ed100_c_termofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_c_termofinal"])){ 
       $sql  .= $virgula." ed100_c_termofinal = '$this->ed100_c_termofinal' ";
       $virgula = ",";
     }
     if(trim($this->ed100_opcional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_opcional"])){ 
       $sql  .= $virgula." ed100_opcional = '$this->ed100_opcional' ";
       $virgula = ",";
       if(trim($this->ed100_opcional) == null ){ 
         $this->erro_sql = " Campo Opcional não informado.";
         $this->erro_campo = "ed100_opcional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed100_basecomum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_basecomum"])){ 
       $sql  .= $virgula." ed100_basecomum = '$this->ed100_basecomum' ";
       $virgula = ",";
       if(trim($this->ed100_basecomum) == null ){ 
         $this->erro_sql = " Campo Base Comum não informado.";
         $this->erro_campo = "ed100_basecomum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed100_i_codigo!=null){
       $sql .= " ed100_i_codigo = $this->ed100_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed100_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009020,'$this->ed100_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_codigo"]) || $this->ed100_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009020,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_i_codigo'))."','$this->ed100_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_historicompsfora"]) || $this->ed100_i_historicompsfora != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009021,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_i_historicompsfora'))."','$this->ed100_i_historicompsfora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_disciplina"]) || $this->ed100_i_disciplina != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009022,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_i_disciplina'))."','$this->ed100_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_justificativa"]) || $this->ed100_i_justificativa != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009023,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_i_justificativa'))."','$this->ed100_i_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_qtdch"]) || $this->ed100_i_qtdch != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009024,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_i_qtdch'))."','$this->ed100_i_qtdch',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_c_resultadofinal"]) || $this->ed100_c_resultadofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009025,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_c_resultadofinal'))."','$this->ed100_c_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_t_resultobtido"]) || $this->ed100_t_resultobtido != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009026,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_t_resultobtido'))."','$this->ed100_t_resultobtido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_c_situacao"]) || $this->ed100_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009027,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_c_situacao'))."','$this->ed100_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_c_tiporesultado"]) || $this->ed100_c_tiporesultado != "")
             $resac = db_query("insert into db_acount values($acount,1010159,1009028,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_c_tiporesultado'))."','$this->ed100_c_tiporesultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_i_ordenacao"]) || $this->ed100_i_ordenacao != "")
             $resac = db_query("insert into db_acount values($acount,1010159,14824,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_i_ordenacao'))."','$this->ed100_i_ordenacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_c_termofinal"]) || $this->ed100_c_termofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010159,19695,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_c_termofinal'))."','$this->ed100_c_termofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_opcional"]) || $this->ed100_opcional != "")
             $resac = db_query("insert into db_acount values($acount,1010159,20323,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_opcional'))."','$this->ed100_opcional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_basecomum"]) || $this->ed100_basecomum != "")
             $resac = db_query("insert into db_acount values($acount,1010159,20664,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_basecomum'))."','$this->ed100_basecomum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Disciplinas do Historico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed100_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Disciplinas do Historico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed100_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed100_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed100_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed100_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009020,'$ed100_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009020,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009021,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_i_historicompsfora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009022,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009023,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009024,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009025,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009026,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_t_resultobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009027,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,1009028,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_c_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,14824,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,19695,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_c_termofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,20323,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_opcional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010159,20664,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_basecomum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from histmpsdiscfora
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed100_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed100_i_codigo = $ed100_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Disciplinas do Historico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed100_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Disciplinas do Historico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed100_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed100_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:histmpsdiscfora";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed100_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histmpsdiscfora ";
     $sql .= "      left join justificativa  on  justificativa.ed06_i_codigo = histmpsdiscfora.ed100_i_justificativa";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = histmpsdiscfora.ed100_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join historicompsfora  on  historicompsfora.ed99_i_codigo = histmpsdiscfora.ed100_i_historicompsfora";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join escolaproc  on  escolaproc.ed82_i_codigo = historicompsfora.ed99_i_escolaproc";
     $sql .= "      left join justificativa as a on  a.ed06_i_codigo = historicompsfora.ed99_i_justificativa";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = historicompsfora.ed99_i_serie";
     $sql .= "      inner join historico  on  historico.ed61_i_codigo = historicompsfora.ed99_i_historico";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = historico.ed61_i_aluno";
     $sql .= "      inner join cursoedu   on  cursoedu.ed29_i_codigo = historico.ed61_i_curso";
     $sql2 = "";
     if($dbwhere==""){
       if($ed100_i_codigo!=null ){
         $sql2 .= " where histmpsdiscfora.ed100_i_codigo = $ed100_i_codigo "; 
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
   function sql_query_file ( $ed100_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histmpsdiscfora ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed100_i_codigo!=null ){
         $sql2 .= " where histmpsdiscfora.ed100_i_codigo = $ed100_i_codigo "; 
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
    $sSql .= " from histmpsdiscfora " ;
    $sSql .= " inner join disciplina on ed12_i_codigo = ed100_i_disciplina ";
    $sSql .= " inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
    $sSql .= " inner join historicompsfora on ed99_i_codigo = ed100_i_historicompsfora ";
    $sSql .= " inner join serie on ed11_i_codigo = ed99_i_serie ";
    $sSql .= " inner join historico on ed61_i_codigo = ed99_i_historico "; 
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where histmpsdiscfora.ed100_i_codigo  = $iCodigo ";
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
   	$sSql .= " from histmpsdiscfora " ;
   	$sSql .= " inner join disciplina on ed12_i_codigo = ed100_i_disciplina              ";
   	$sSql .= " inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina         ";
   	$sSql .= " inner join historicompsfora on ed99_i_codigo = ed100_i_historicompsfora  ";
   	$sSql .= " inner join serie on ed11_i_codigo = ed99_i_serie                         ";
   	$sSql .= " inner join historico on ed61_i_codigo = ed99_i_historico                 ";
   	$sSql .= " inner join cursoedu on ed29_i_codigo = ed61_i_curso                      ";
   
   	$sSql2 = '';
   	if ($sDbWhere == '') {
   
   		if ($iCodigo != null ){
   			$sSql2 .= " where histmpsdiscfora.ed100_i_codigo  = $iCodigo ";
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
}
?>