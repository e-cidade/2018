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
//CLASSE DA ENTIDADE historicompd
class cl_historicompd { 
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
   var $ed64_i_codigo = 0; 
   var $ed64_i_historico = 0; 
   var $ed64_i_escola = 0; 
   var $ed64_i_disciplina = 0; 
   var $ed64_i_justificativa = 0; 
   var $ed64_i_anoref = 0; 
   var $ed64_i_periodoref = 0; 
   var $ed64_c_resultadofinal = null; 
   var $ed64_c_situacao = null; 
   var $ed64_t_resultobtido = null; 
   var $ed64_i_qtdch = 0; 
   var $ed64_c_tiporesultado = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed64_i_codigo = int8 = Código 
                 ed64_i_historico = int8 = Histórico 
                 ed64_i_escola = int8 = Escola 
                 ed64_i_disciplina = int8 = Disciplina 
                 ed64_i_justificativa = int8 = Justificativa 
                 ed64_i_anoref = int4 = Ano de Referência 
                 ed64_i_periodoref = int4 = Período de Referência 
                 ed64_c_resultadofinal = char(1) = Resultado Final 
                 ed64_c_situacao = char(20) = Situação 
                 ed64_t_resultobtido = text = Resultado Obtido 
                 ed64_i_qtdch = int4 = Carga Horária 
                 ed64_c_tiporesultado = char(1) = Tipo de Resultado 
                 ";
   //funcao construtor da classe 
   function cl_historicompd() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("historicompd"); 
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
       $this->ed64_i_codigo = ($this->ed64_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_codigo"]:$this->ed64_i_codigo);
       $this->ed64_i_historico = ($this->ed64_i_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_historico"]:$this->ed64_i_historico);
       $this->ed64_i_escola = ($this->ed64_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_escola"]:$this->ed64_i_escola);
       $this->ed64_i_disciplina = ($this->ed64_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_disciplina"]:$this->ed64_i_disciplina);
       $this->ed64_i_justificativa = ($this->ed64_i_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_justificativa"]:$this->ed64_i_justificativa);
       $this->ed64_i_anoref = ($this->ed64_i_anoref == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_anoref"]:$this->ed64_i_anoref);
       $this->ed64_i_periodoref = ($this->ed64_i_periodoref == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_periodoref"]:$this->ed64_i_periodoref);
       $this->ed64_c_resultadofinal = ($this->ed64_c_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_c_resultadofinal"]:$this->ed64_c_resultadofinal);
       $this->ed64_c_situacao = ($this->ed64_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_c_situacao"]:$this->ed64_c_situacao);
       $this->ed64_t_resultobtido = ($this->ed64_t_resultobtido == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_t_resultobtido"]:$this->ed64_t_resultobtido);
       $this->ed64_i_qtdch = ($this->ed64_i_qtdch == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_qtdch"]:$this->ed64_i_qtdch);
       $this->ed64_c_tiporesultado = ($this->ed64_c_tiporesultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_c_tiporesultado"]:$this->ed64_c_tiporesultado);
     }else{
       $this->ed64_i_codigo = ($this->ed64_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed64_i_codigo"]:$this->ed64_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed64_i_codigo){ 
      $this->atualizacampos();
     if($this->ed64_i_historico == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "ed64_i_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed64_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed64_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed64_i_disciplina == null ){ 
       $this->erro_sql = " Campo Disciplina nao Informado.";
       $this->erro_campo = "ed64_i_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed64_i_justificativa == null ){ 
       $this->ed64_i_justificativa = "null";
     }
     if($this->ed64_i_anoref == null ){ 
       $this->erro_sql = " Campo Ano de Referência nao Informado.";
       $this->erro_campo = "ed64_i_anoref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed64_i_periodoref == null ){ 
       $this->erro_sql = " Campo Período de Referência nao Informado.";
       $this->erro_campo = "ed64_i_periodoref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed64_c_resultadofinal == null ){ 
       $this->erro_sql = " Campo Resultado Final nao Informado.";
       $this->erro_campo = "ed64_c_resultadofinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed64_c_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "ed64_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed64_t_resultobtido == null ){ 
       $this->erro_sql = " Campo Resultado Obtido nao Informado.";
       $this->erro_campo = "ed64_t_resultobtido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed64_i_qtdch == null ){ 
       $this->ed64_i_qtdch = "null";
     }
     if($this->ed64_c_tiporesultado == null ){ 
       $this->erro_sql = " Campo Tipo de Resultado nao Informado.";
       $this->erro_campo = "ed64_c_tiporesultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed64_i_codigo == "" || $ed64_i_codigo == null ){
       $result = db_query("select nextval('historicompd_ed64_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: historicompd_ed64_i_codigo_seq do campo: ed64_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed64_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from historicompd_ed64_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed64_i_codigo)){
         $this->erro_sql = " Campo ed64_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed64_i_codigo = $ed64_i_codigo; 
       }
     }
     if(($this->ed64_i_codigo == null) || ($this->ed64_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed64_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into historicompd(
                                       ed64_i_codigo 
                                      ,ed64_i_historico 
                                      ,ed64_i_escola 
                                      ,ed64_i_disciplina 
                                      ,ed64_i_justificativa 
                                      ,ed64_i_anoref 
                                      ,ed64_i_periodoref 
                                      ,ed64_c_resultadofinal 
                                      ,ed64_c_situacao 
                                      ,ed64_t_resultobtido 
                                      ,ed64_i_qtdch 
                                      ,ed64_c_tiporesultado 
                       )
                values (
                                $this->ed64_i_codigo 
                               ,$this->ed64_i_historico 
                               ,$this->ed64_i_escola 
                               ,$this->ed64_i_disciplina 
                               ,$this->ed64_i_justificativa 
                               ,$this->ed64_i_anoref 
                               ,$this->ed64_i_periodoref 
                               ,'$this->ed64_c_resultadofinal' 
                               ,'$this->ed64_c_situacao' 
                               ,'$this->ed64_t_resultobtido' 
                               ,$this->ed64_i_qtdch 
                               ,'$this->ed64_c_tiporesultado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Historico MPD ($this->ed64_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Historico MPD já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Historico MPD ($this->ed64_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed64_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed64_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008755,'$this->ed64_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010131,1008755,'','".AddSlashes(pg_result($resaco,0,'ed64_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008756,'','".AddSlashes(pg_result($resaco,0,'ed64_i_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008757,'','".AddSlashes(pg_result($resaco,0,'ed64_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008758,'','".AddSlashes(pg_result($resaco,0,'ed64_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008759,'','".AddSlashes(pg_result($resaco,0,'ed64_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008760,'','".AddSlashes(pg_result($resaco,0,'ed64_i_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008761,'','".AddSlashes(pg_result($resaco,0,'ed64_i_periodoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008762,'','".AddSlashes(pg_result($resaco,0,'ed64_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008763,'','".AddSlashes(pg_result($resaco,0,'ed64_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008764,'','".AddSlashes(pg_result($resaco,0,'ed64_t_resultobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008765,'','".AddSlashes(pg_result($resaco,0,'ed64_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010131,1008766,'','".AddSlashes(pg_result($resaco,0,'ed64_c_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed64_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update historicompd set ";
     $virgula = "";
     if(trim($this->ed64_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_codigo"])){ 
       $sql  .= $virgula." ed64_i_codigo = $this->ed64_i_codigo ";
       $virgula = ",";
       if(trim($this->ed64_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed64_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_i_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_historico"])){ 
       $sql  .= $virgula." ed64_i_historico = $this->ed64_i_historico ";
       $virgula = ",";
       if(trim($this->ed64_i_historico) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "ed64_i_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_escola"])){ 
       $sql  .= $virgula." ed64_i_escola = $this->ed64_i_escola ";
       $virgula = ",";
       if(trim($this->ed64_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed64_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_disciplina"])){ 
       $sql  .= $virgula." ed64_i_disciplina = $this->ed64_i_disciplina ";
       $virgula = ",";
       if(trim($this->ed64_i_disciplina) == null ){ 
         $this->erro_sql = " Campo Disciplina nao Informado.";
         $this->erro_campo = "ed64_i_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_i_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_justificativa"])){ 
        if(trim($this->ed64_i_justificativa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_justificativa"])){ 
           $this->ed64_i_justificativa = "0" ; 
        } 
       $sql  .= $virgula." ed64_i_justificativa = $this->ed64_i_justificativa ";
       $virgula = ",";
     }
     if(trim($this->ed64_i_anoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_anoref"])){ 
       $sql  .= $virgula." ed64_i_anoref = $this->ed64_i_anoref ";
       $virgula = ",";
       if(trim($this->ed64_i_anoref) == null ){ 
         $this->erro_sql = " Campo Ano de Referência nao Informado.";
         $this->erro_campo = "ed64_i_anoref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_i_periodoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_periodoref"])){ 
       $sql  .= $virgula." ed64_i_periodoref = $this->ed64_i_periodoref ";
       $virgula = ",";
       if(trim($this->ed64_i_periodoref) == null ){ 
         $this->erro_sql = " Campo Período de Referência nao Informado.";
         $this->erro_campo = "ed64_i_periodoref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_c_resultadofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_c_resultadofinal"])){ 
       $sql  .= $virgula." ed64_c_resultadofinal = '$this->ed64_c_resultadofinal' ";
       $virgula = ",";
       if(trim($this->ed64_c_resultadofinal) == null ){ 
         $this->erro_sql = " Campo Resultado Final nao Informado.";
         $this->erro_campo = "ed64_c_resultadofinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_c_situacao"])){ 
       $sql  .= $virgula." ed64_c_situacao = '$this->ed64_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed64_c_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "ed64_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_t_resultobtido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_t_resultobtido"])){ 
       $sql  .= $virgula." ed64_t_resultobtido = '$this->ed64_t_resultobtido' ";
       $virgula = ",";
       if(trim($this->ed64_t_resultobtido) == null ){ 
         $this->erro_sql = " Campo Resultado Obtido nao Informado.";
         $this->erro_campo = "ed64_t_resultobtido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed64_i_qtdch)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_qtdch"])){ 
        if(trim($this->ed64_i_qtdch)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_qtdch"])){ 
           $this->ed64_i_qtdch = "0" ; 
        } 
       $sql  .= $virgula." ed64_i_qtdch = $this->ed64_i_qtdch ";
       $virgula = ",";
     }
     if(trim($this->ed64_c_tiporesultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed64_c_tiporesultado"])){ 
       $sql  .= $virgula." ed64_c_tiporesultado = '$this->ed64_c_tiporesultado' ";
       $virgula = ",";
       if(trim($this->ed64_c_tiporesultado) == null ){ 
         $this->erro_sql = " Campo Tipo de Resultado nao Informado.";
         $this->erro_campo = "ed64_c_tiporesultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed64_i_codigo!=null){
       $sql .= " ed64_i_codigo = $this->ed64_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed64_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008755,'$this->ed64_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008755,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_i_codigo'))."','$this->ed64_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_historico"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008756,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_i_historico'))."','$this->ed64_i_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008757,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_i_escola'))."','$this->ed64_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_disciplina"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008758,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_i_disciplina'))."','$this->ed64_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_justificativa"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008759,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_i_justificativa'))."','$this->ed64_i_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_anoref"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008760,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_i_anoref'))."','$this->ed64_i_anoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_periodoref"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008761,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_i_periodoref'))."','$this->ed64_i_periodoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_c_resultadofinal"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008762,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_c_resultadofinal'))."','$this->ed64_c_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_c_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008763,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_c_situacao'))."','$this->ed64_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_t_resultobtido"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008764,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_t_resultobtido'))."','$this->ed64_t_resultobtido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_i_qtdch"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008765,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_i_qtdch'))."','$this->ed64_i_qtdch',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed64_c_tiporesultado"]))
           $resac = db_query("insert into db_acount values($acount,1010131,1008766,'".AddSlashes(pg_result($resaco,$conresaco,'ed64_c_tiporesultado'))."','$this->ed64_c_tiporesultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico MPD nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed64_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Historico MPD nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed64_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed64_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed64_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed64_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008755,'$ed64_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010131,1008755,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008756,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_i_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008757,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008758,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008759,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008760,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_i_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008761,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_i_periodoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008762,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008763,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008764,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_t_resultobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008765,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010131,1008766,'','".AddSlashes(pg_result($resaco,$iresaco,'ed64_c_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from historicompd
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed64_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed64_i_codigo = $ed64_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico MPD nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed64_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Historico MPD nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed64_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed64_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:historicompd";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed64_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from historicompd ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = historicompd.ed64_i_escola";
     $sql .= "      inner join justificativa  on  justificativa.ed06_i_codigo = historicompd.ed64_i_justificativa";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = historicompd.ed64_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join historico  on  historico.ed61_i_codigo = historicompd.ed64_i_historico";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = historico.ed61_i_escola";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = historico.ed61_i_curso";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = historico.ed61_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed64_i_codigo!=null ){
         $sql2 .= " where historicompd.ed64_i_codigo = $ed64_i_codigo "; 
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
   function sql_query_file ( $ed64_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from historicompd ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed64_i_codigo!=null ){
         $sql2 .= " where historicompd.ed64_i_codigo = $ed64_i_codigo "; 
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