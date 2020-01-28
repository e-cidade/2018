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

//MODULO: pessoal
//CLASSE DA ENTIDADE movrel
class cl_movrel { 
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
   var $r54_instit = 0; 
   var $r54_anomes = null; 
   var $r54_codrel = null; 
   var $r54_regist = 0; 
   var $r54_codeve = null; 
   var $r54_quant1 = 0; 
   var $r54_quant2 = 0; 
   var $r54_quant3 = 0; 
   var $r54_lancad = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r54_instit = int4 = Cod. Instituição 
                 r54_anomes = char(6) = Ano e Mes de leitura dados 
                 r54_codrel = char(4) = Convênio 
                 r54_regist = float8 = Servidor 
                 r54_codeve = char(4) = Relacionamento 
                 r54_quant1 = float8 = Rubrica 1 
                 r54_quant2 = float8 = Rubrica 2 
                 r54_quant3 = float8 = Rubrica 3 
                 r54_lancad = bool = Lançado 
                 ";
   //funcao construtor da classe 
   function cl_movrel() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("movrel"); 
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
       $this->r54_instit = ($this->r54_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r54_instit"]:$this->r54_instit);
       $this->r54_anomes = ($this->r54_anomes == ""?@$GLOBALS["HTTP_POST_VARS"]["r54_anomes"]:$this->r54_anomes);
       $this->r54_codrel = ($this->r54_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["r54_codrel"]:$this->r54_codrel);
       $this->r54_regist = ($this->r54_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r54_regist"]:$this->r54_regist);
       $this->r54_codeve = ($this->r54_codeve == ""?@$GLOBALS["HTTP_POST_VARS"]["r54_codeve"]:$this->r54_codeve);
       $this->r54_quant1 = ($this->r54_quant1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r54_quant1"]:$this->r54_quant1);
       $this->r54_quant2 = ($this->r54_quant2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r54_quant2"]:$this->r54_quant2);
       $this->r54_quant3 = ($this->r54_quant3 == ""?@$GLOBALS["HTTP_POST_VARS"]["r54_quant3"]:$this->r54_quant3);
       $this->r54_lancad = ($this->r54_lancad == "f"?@$GLOBALS["HTTP_POST_VARS"]["r54_lancad"]:$this->r54_lancad);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->r54_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "r54_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r54_anomes == null ){ 
       $this->erro_sql = " Campo Ano e Mes de leitura dados nao Informado.";
       $this->erro_campo = "r54_anomes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r54_codrel == null ){ 
       $this->erro_sql = " Campo Convênio nao Informado.";
       $this->erro_campo = "r54_codrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r54_regist == null ){ 
       $this->erro_sql = " Campo Servidor nao Informado.";
       $this->erro_campo = "r54_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r54_codeve == null ){ 
       $this->erro_sql = " Campo Relacionamento nao Informado.";
       $this->erro_campo = "r54_codeve";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r54_quant1 == null ){ 
       $this->erro_sql = " Campo Rubrica 1 nao Informado.";
       $this->erro_campo = "r54_quant1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r54_quant2 == null ){ 
       $this->erro_sql = " Campo Rubrica 2 nao Informado.";
       $this->erro_campo = "r54_quant2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r54_quant3 == null ){ 
       $this->erro_sql = " Campo Rubrica 3 nao Informado.";
       $this->erro_campo = "r54_quant3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r54_lancad == null ){ 
       $this->erro_sql = " Campo Lançado nao Informado.";
       $this->erro_campo = "r54_lancad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into movrel(
                                       r54_instit 
                                      ,r54_anomes 
                                      ,r54_codrel 
                                      ,r54_regist 
                                      ,r54_codeve 
                                      ,r54_quant1 
                                      ,r54_quant2 
                                      ,r54_quant3 
                                      ,r54_lancad 
                       )
                values (
                                $this->r54_instit 
                               ,'$this->r54_anomes' 
                               ,'$this->r54_codrel' 
                               ,$this->r54_regist 
                               ,'$this->r54_codeve' 
                               ,$this->r54_quant1 
                               ,$this->r54_quant2 
                               ,$this->r54_quant3 
                               ,'$this->r54_lancad' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arquivo gerado pelo sistema a partir da leitura de () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arquivo gerado pelo sistema a partir da leitura de já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arquivo gerado pelo sistema a partir da leitura de () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update movrel set ";
     $virgula = "";
     $sql1 = '';
     if(trim($this->r54_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_instit"])){ 
       $sql  .= $virgula." r54_instit = $this->r54_instit ";
       $sql1 .= " and r54_instit = $this->r54_instit ";
       $virgula = ",";
       if(trim($this->r54_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r54_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r54_anomes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_anomes"])){ 
       $sql  .= $virgula." r54_anomes = '$this->r54_anomes' ";
       $sql1 .= " and r54_anomes = '$this->r54_anomes' ";
       $virgula = ",";
       if(trim($this->r54_anomes) == null ){ 
         $this->erro_sql = " Campo Ano e Mes de leitura dados nao Informado.";
         $this->erro_campo = "r54_anomes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r54_codrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_codrel"])){ 
       $sql  .= $virgula." r54_codrel = '$this->r54_codrel' ";
       $sql1 .= " and r54_codrel = '$this->r54_codrel' ";
       $virgula = ",";
       if(trim($this->r54_codrel) == null ){ 
         $this->erro_sql = " Campo Convênio nao Informado.";
         $this->erro_campo = "r54_codrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r54_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_regist"])){ 
       $sql  .= $virgula." r54_regist = $this->r54_regist ";
       $sql1 .= " and r54_regist = $this->r54_regist ";
       $virgula = ",";
       if(trim($this->r54_regist) == null ){ 
         $this->erro_sql = " Campo Servidor nao Informado.";
         $this->erro_campo = "r54_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r54_codeve)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_codeve"])){ 
       $sql  .= $virgula." r54_codeve = '$this->r54_codeve' ";
       $sql1 .= " and r54_codeve = '$this->r54_codeve' ";
       $virgula = ",";
       if(trim($this->r54_codeve) == null ){ 
         $this->erro_sql = " Campo Relacionamento nao Informado.";
         $this->erro_campo = "r54_codeve";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r54_quant1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_quant1"])){ 
       $sql  .= $virgula." r54_quant1 = $this->r54_quant1 ";
       $sql1 .= " and r54_quant1 = $this->r54_quant1 ";
       $virgula = ",";
       if(trim($this->r54_quant1) == null ){ 
         $this->erro_sql = " Campo Rubrica 1 nao Informado.";
         $this->erro_campo = "r54_quant1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r54_quant2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_quant2"])){ 
       $sql  .= $virgula." r54_quant2 = $this->r54_quant2 ";
       $sql1 .= " and r54_quant2 = $this->r54_quant2 ";
       $virgula = ",";
       if(trim($this->r54_quant2) == null ){ 
         $this->erro_sql = " Campo Rubrica 2 nao Informado.";
         $this->erro_campo = "r54_quant2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r54_quant3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_quant3"])){ 
       $sql  .= $virgula." r54_quant3 = $this->r54_quant3 ";
       $sql1 .= " and r54_quant3 = $this->r54_quant3 ";
       $virgula = ",";
       if(trim($this->r54_quant3) == null ){ 
         $this->erro_sql = " Campo Rubrica 3 nao Informado.";
         $this->erro_campo = "r54_quant3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r54_lancad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r54_lancad"])){ 
       $sql  .= $virgula." r54_lancad = '$this->r54_lancad' ";
       $virgula = ",";
       if(trim($this->r54_lancad) == null ){ 
         $this->erro_sql = " Campo Lançado nao Informado.";
         $this->erro_campo = "r54_lancad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where 1 = 1 ".$sql1;
//     $sql .= "oid = '$oid'";
//     echo "<br><br>  sql_movrel --> ".$sql;
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arquivo gerado pelo sistema a partir da leitura de nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arquivo gerado pelo sistema a partir da leitura de nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from movrel
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arquivo gerado pelo sistema a partir da leitura de nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arquivo gerado pelo sistema a partir da leitura de nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:movrel";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="movrel.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from movrel ";
     $sql .= "      inner join db_config  on  db_config.codigo = movrel.r54_instit";
     $sql .= "      inner join convenio  on  convenio.r56_codrel = movrel.r54_codrel";
     $sql .= "      inner join relac  on  relac.r55_codeve = movrel.r54_codeve";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where movrel.oid = '$oid'";
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
   function sql_query_dados ( $oid = null,$campos="movrel.oid,*",$ordem=null,$dbwhere="",$ano="",$mes=""){ 
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
     if($ano == ""){
     	 $ano = db_anofolha();
     }
     if($mes == ""){
     	 $mes = db_mesfolha();
     }
     $sql .= " from movrel ";
     $sql .= "      inner join convenio  on convenio.r56_codrel   = movrel.r54_codrel
                                        and convenio.r56_instit   = ".db_getsession('DB_instit');
     $sql .= "      inner join relac     on relac.r55_codeve      = movrel.r54_codeve 
                                        and relac.r55_instit      = ".db_getsession('DB_instit');
     $sql .= "      left join rhpessoalmov on rhpessoalmov.rh02_regist = movrel.r54_regist
                                          and rhpessoalmov.rh02_anousu = $ano
                                          and rhpessoalmov.rh02_mesusu = $mes 
					  and rhpessoalmov.rh02_instit = ".db_getsession('DB_instit');
     $sql .= "      left join rhpessoal on rhpessoal.rh01_regist = rh02_regist ";
     $sql .= "      left join cgm       on cgm.z01_numcgm        = rhpessoal.rh01_numcgm";
     $sql .= "      left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where movrel.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from movrel ";
     $sql2 = "";
     if($dbwhere==""){
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
   function sql_query_gerfsal ( $oid = null,$campos="movrel.oid,*",$ordem=null,$dbwhere="",$ano="",$mes="",$rubric="",$regist=""){ 
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
     if($ano == ""){
     	 $ano = db_anofolha();
     }
     if($mes == ""){
     	 $mes = db_mesfolha();
     }
     $sql .= " from movrel ";
     $sql .= "      left join gerfsal on gerfsal.r14_anousu = ".$ano."
                                     and gerfsal.r14_mesusu = ".$mes."
                                     and gerfsal.r14_instit = movrel.r54_instit
                                     and gerfsal.r14_rubric = '".$rubric."'
                                     and gerfsal.r14_regist = movrel.r54_regist
             ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where movrel.oid = '$oid'";
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