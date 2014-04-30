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
//CLASSE DA ENTIDADE progress
class cl_progress { 
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
   var $r24_instit = 0; 
   var $r24_anousu = 0; 
   var $r24_mesusu = 0; 
   var $r24_regime = 0; 
   var $r24_progr = null; 
   var $r24_descr = null; 
   var $r24_perc = 0; 
   var $r24_ano = 0; 
   var $r24_padrao = null; 
   var $r24_meses = 0; 
   var $r24_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r24_instit = int4 = Cod. Instituição 
                 r24_anousu = int4 = Ano do Exercicio 
                 r24_mesusu = int4 = Mes do Exercicio 
                 r24_regime = int4 = Código do Regime 
                 r24_progr = char(2) = Código da Progressão 
                 r24_descr = char(30) = Descrição da Progressão 
                 r24_perc = float8 = Percentual da Faixa de Progressão 
                 r24_ano = int4 = Numero de anos para progressao 
                 r24_padrao = char(    10) = Codigo do Padrao do Func. 
                 r24_meses = int4 = Meses para calculo progressao 
                 r24_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_progress() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progress"); 
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
       $this->r24_instit = ($this->r24_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_instit"]:$this->r24_instit);
       $this->r24_anousu = ($this->r24_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_anousu"]:$this->r24_anousu);
       $this->r24_mesusu = ($this->r24_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_mesusu"]:$this->r24_mesusu);
       $this->r24_regime = ($this->r24_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_regime"]:$this->r24_regime);
       $this->r24_progr = ($this->r24_progr == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_progr"]:$this->r24_progr);
       $this->r24_descr = ($this->r24_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_descr"]:$this->r24_descr);
       $this->r24_perc = ($this->r24_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_perc"]:$this->r24_perc);
       $this->r24_ano = ($this->r24_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_ano"]:$this->r24_ano);
       $this->r24_padrao = ($this->r24_padrao == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_padrao"]:$this->r24_padrao);
       $this->r24_meses = ($this->r24_meses == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_meses"]:$this->r24_meses);
       $this->r24_valor = ($this->r24_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_valor"]:$this->r24_valor);
     }else{
       $this->r24_instit = ($this->r24_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_instit"]:$this->r24_instit);
       $this->r24_anousu = ($this->r24_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_anousu"]:$this->r24_anousu);
       $this->r24_mesusu = ($this->r24_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_mesusu"]:$this->r24_mesusu);
       $this->r24_regime = ($this->r24_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_regime"]:$this->r24_regime);
       $this->r24_ano = ($this->r24_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_ano"]:$this->r24_ano);
       $this->r24_padrao = ($this->r24_padrao == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_padrao"]:$this->r24_padrao);
       $this->r24_meses = ($this->r24_meses == ""?@$GLOBALS["HTTP_POST_VARS"]["r24_meses"]:$this->r24_meses);
     }
   }
   // funcao para inclusao
   function incluir ($r24_anousu,$r24_mesusu,$r24_regime,$r24_padrao,$r24_meses,$r24_instit){ 
      $this->atualizacampos();
     if($this->r24_progr == null ){ 
       $this->erro_sql = " Campo Código da Progressão nao Informado.";
       $this->erro_campo = "r24_progr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r24_descr == null ){ 
       $this->erro_sql = " Campo Descrição da Progressão nao Informado.";
       $this->erro_campo = "r24_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r24_perc == null ){ 
       $this->erro_sql = " Campo Percentual da Faixa de Progressão nao Informado.";
       $this->erro_campo = "r24_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r24_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r24_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r24_anousu = $r24_anousu; 
       $this->r24_mesusu = $r24_mesusu; 
       $this->r24_regime = $r24_regime; 
       $this->r24_padrao = $r24_padrao; 
       $this->r24_meses = $r24_meses; 
       $this->r24_instit = $r24_instit; 
     if(($this->r24_anousu == null) || ($this->r24_anousu == "") ){ 
       $this->erro_sql = " Campo r24_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r24_mesusu == null) || ($this->r24_mesusu == "") ){ 
       $this->erro_sql = " Campo r24_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r24_regime == null) || ($this->r24_regime == "") ){ 
       $this->erro_sql = " Campo r24_regime nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r24_padrao == null) || ($this->r24_padrao == "") ){ 
       $this->erro_sql = " Campo r24_padrao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r24_meses == null) || ($this->r24_meses == "") ){ 
       $this->erro_sql = " Campo r24_meses nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r24_instit == null) || ($this->r24_instit == "") ){ 
       $this->erro_sql = " Campo r24_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progress(
                                       r24_instit 
                                      ,r24_anousu 
                                      ,r24_mesusu 
                                      ,r24_regime 
                                      ,r24_progr 
                                      ,r24_descr 
                                      ,r24_perc 
                                      ,r24_ano 
                                      ,r24_padrao 
                                      ,r24_meses 
                                      ,r24_valor 
                       )
                values (
                                $this->r24_instit 
                               ,$this->r24_anousu 
                               ,$this->r24_mesusu 
                               ,$this->r24_regime 
                               ,'$this->r24_progr' 
                               ,'$this->r24_descr' 
                               ,$this->r24_perc 
                               ,$this->r24_ano 
                               ,'$this->r24_padrao' 
                               ,$this->r24_meses 
                               ,$this->r24_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das Progressoes                           ($this->r24_anousu."-".$this->r24_mesusu."-".$this->r24_regime."-".$this->r24_padrao."-".$this->r24_meses."-".$this->r24_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das Progressoes                           já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das Progressoes                           ($this->r24_anousu."-".$this->r24_mesusu."-".$this->r24_regime."-".$this->r24_padrao."-".$this->r24_meses."-".$this->r24_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r24_anousu."-".$this->r24_mesusu."-".$this->r24_regime."-".$this->r24_padrao."-".$this->r24_meses."-".$this->r24_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r24_anousu,$this->r24_mesusu,$this->r24_regime,$this->r24_padrao,$this->r24_meses,$this->r24_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4350,'$this->r24_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4351,'$this->r24_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4352,'$this->r24_regime','I')");
       $resac = db_query("insert into db_acountkey values($acount,4357,'$this->r24_padrao','I')");
       $resac = db_query("insert into db_acountkey values($acount,4358,'$this->r24_meses','I')");
       $resac = db_query("insert into db_acountkey values($acount,9897,'$this->r24_instit','I')");
       $resac = db_query("insert into db_acount values($acount,583,9897,'','".AddSlashes(pg_result($resaco,0,'r24_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4350,'','".AddSlashes(pg_result($resaco,0,'r24_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4351,'','".AddSlashes(pg_result($resaco,0,'r24_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4352,'','".AddSlashes(pg_result($resaco,0,'r24_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4353,'','".AddSlashes(pg_result($resaco,0,'r24_progr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4354,'','".AddSlashes(pg_result($resaco,0,'r24_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4355,'','".AddSlashes(pg_result($resaco,0,'r24_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4356,'','".AddSlashes(pg_result($resaco,0,'r24_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4357,'','".AddSlashes(pg_result($resaco,0,'r24_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4358,'','".AddSlashes(pg_result($resaco,0,'r24_meses'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,583,4359,'','".AddSlashes(pg_result($resaco,0,'r24_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r24_anousu=null,$r24_mesusu=null,$r24_regime=null,$r24_padrao=null,$r24_meses=null,$r24_instit=null) { 
      $this->atualizacampos();
     $sql = " update progress set ";
     $virgula = "";
     if(trim($this->r24_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_instit"])){ 
       $sql  .= $virgula." r24_instit = $this->r24_instit ";
       $virgula = ",";
       if(trim($this->r24_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r24_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r24_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_anousu"])){ 
       $sql  .= $virgula." r24_anousu = $this->r24_anousu ";
       $virgula = ",";
       if(trim($this->r24_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r24_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r24_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_mesusu"])){ 
       $sql  .= $virgula." r24_mesusu = $this->r24_mesusu ";
       $virgula = ",";
       if(trim($this->r24_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r24_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r24_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_regime"])){ 
       $sql  .= $virgula." r24_regime = $this->r24_regime ";
       $virgula = ",";
       if(trim($this->r24_regime) == null ){ 
         $this->erro_sql = " Campo Código do Regime nao Informado.";
         $this->erro_campo = "r24_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     /*
     if(trim($this->r24_progr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_progr"])){ 
       $sql  .= $virgula." r24_progr = '$this->r24_progr' ";
       $virgula = ",";
       if(trim($this->r24_progr) == null ){ 
         $this->erro_sql = " Campo Código da Progressão nao Informado.";
         $this->erro_campo = "r24_progr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     */
     if(trim($this->r24_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_descr"])){ 
       $sql  .= $virgula." r24_descr = '$this->r24_descr' ";
       $virgula = ",";
       if(trim($this->r24_descr) == null ){ 
         $this->erro_sql = " Campo Descrição da Progressão nao Informado.";
         $this->erro_campo = "r24_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r24_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_perc"])){ 
       $sql  .= $virgula." r24_perc = $this->r24_perc ";
       $virgula = ",";
       if(trim($this->r24_perc) == null ){ 
         $this->erro_sql = " Campo Percentual da Faixa de Progressão nao Informado.";
         $this->erro_campo = "r24_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r24_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_ano"])){ 
       $sql  .= $virgula." r24_ano = $this->r24_ano ";
       $virgula = ",";
       if(trim($this->r24_ano) == null ){ 
         $this->erro_sql = " Campo Numero de anos para progressao nao Informado.";
         $this->erro_campo = "r24_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r24_padrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_padrao"])){ 
       $sql  .= $virgula." r24_padrao = '$this->r24_padrao' ";
       $virgula = ",";
       if(trim($this->r24_padrao) == null ){ 
         $this->erro_sql = " Campo Codigo do Padrao do Func. nao Informado.";
         $this->erro_campo = "r24_padrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r24_meses)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_meses"])){ 
       $sql  .= $virgula." r24_meses = $this->r24_meses ";
       $virgula = ",";
       if(trim($this->r24_meses) == null ){ 
         $this->erro_sql = " Campo Meses para calculo progressao nao Informado.";
         $this->erro_campo = "r24_meses";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r24_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r24_valor"])){ 
       $sql  .= $virgula." r24_valor = $this->r24_valor ";
       $virgula = ",";
       if(trim($this->r24_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r24_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r24_anousu!=null){
       $sql .= " r24_anousu = $this->r24_anousu";
     }
     if($r24_mesusu!=null){
       $sql .= " and  r24_mesusu = $this->r24_mesusu";
     }
     if($r24_regime!=null){
       $sql .= " and  r24_regime = $this->r24_regime";
     }
     if($r24_padrao!=null){
       $sql .= " and  r24_padrao = '$this->r24_padrao'";
     }
     if($r24_meses!=null){
       $sql .= " and  r24_meses = ".$r24_meses;
     }
     if($r24_instit!=null){
       $sql .= " and  r24_instit = $this->r24_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r24_anousu,$this->r24_mesusu,$this->r24_regime,$this->r24_padrao,$this->r24_meses,$this->r24_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4350,'$this->r24_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4351,'$this->r24_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4352,'$this->r24_regime','A')");
         $resac = db_query("insert into db_acountkey values($acount,4357,'$this->r24_padrao','A')");
         $resac = db_query("insert into db_acountkey values($acount,4358,'$this->r24_meses','A')");
         $resac = db_query("insert into db_acountkey values($acount,9897,'$this->r24_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_instit"]))
           $resac = db_query("insert into db_acount values($acount,583,9897,'".AddSlashes(pg_result($resaco,$conresaco,'r24_instit'))."','$this->r24_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_anousu"]))
           $resac = db_query("insert into db_acount values($acount,583,4350,'".AddSlashes(pg_result($resaco,$conresaco,'r24_anousu'))."','$this->r24_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,583,4351,'".AddSlashes(pg_result($resaco,$conresaco,'r24_mesusu'))."','$this->r24_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_regime"]))
           $resac = db_query("insert into db_acount values($acount,583,4352,'".AddSlashes(pg_result($resaco,$conresaco,'r24_regime'))."','$this->r24_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_progr"]))
           $resac = db_query("insert into db_acount values($acount,583,4353,'".AddSlashes(pg_result($resaco,$conresaco,'r24_progr'))."','$this->r24_progr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_descr"]))
           $resac = db_query("insert into db_acount values($acount,583,4354,'".AddSlashes(pg_result($resaco,$conresaco,'r24_descr'))."','$this->r24_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_perc"]))
           $resac = db_query("insert into db_acount values($acount,583,4355,'".AddSlashes(pg_result($resaco,$conresaco,'r24_perc'))."','$this->r24_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_ano"]))
           $resac = db_query("insert into db_acount values($acount,583,4356,'".AddSlashes(pg_result($resaco,$conresaco,'r24_ano'))."','$this->r24_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_padrao"]))
           $resac = db_query("insert into db_acount values($acount,583,4357,'".AddSlashes(pg_result($resaco,$conresaco,'r24_padrao'))."','$this->r24_padrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_meses"]))
           $resac = db_query("insert into db_acount values($acount,583,4358,'".AddSlashes(pg_result($resaco,$conresaco,'r24_meses'))."','$this->r24_meses',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r24_valor"]))
           $resac = db_query("insert into db_acount values($acount,583,4359,'".AddSlashes(pg_result($resaco,$conresaco,'r24_valor'))."','$this->r24_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Progressoes                           nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r24_anousu."-".$this->r24_mesusu."-".$this->r24_regime."-".$this->r24_padrao."-".$this->r24_meses."-".$this->r24_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Progressoes                           nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r24_anousu."-".$this->r24_mesusu."-".$this->r24_regime."-".$this->r24_padrao."-".$this->r24_meses."-".$this->r24_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r24_anousu."-".$this->r24_mesusu."-".$this->r24_regime."-".$this->r24_padrao."-".$this->r24_meses."-".$this->r24_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r24_anousu=null,$r24_mesusu=null,$r24_regime=null,$r24_padrao=null,$r24_meses=null,$r24_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r24_anousu,$r24_mesusu,$r24_regime,$r24_padrao,$r24_meses,$r24_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4350,'$r24_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4351,'$r24_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4352,'$r24_regime','E')");
         $resac = db_query("insert into db_acountkey values($acount,4357,'$r24_padrao','E')");
         $resac = db_query("insert into db_acountkey values($acount,4358,'$r24_meses','E')");
         $resac = db_query("insert into db_acountkey values($acount,9897,'$r24_instit','E')");
         $resac = db_query("insert into db_acount values($acount,583,9897,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4350,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4351,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4352,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4353,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_progr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4354,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4355,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4356,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4357,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4358,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_meses'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,583,4359,'','".AddSlashes(pg_result($resaco,$iresaco,'r24_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progress
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r24_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r24_anousu = $r24_anousu ";
        }
        if($r24_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r24_mesusu = $r24_mesusu ";
        }
        if($r24_regime != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r24_regime = $r24_regime ";
        }
        if($r24_padrao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r24_padrao = '$r24_padrao' ";
        }
        if($r24_meses != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r24_meses = $r24_meses ";
        }
        if($r24_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r24_instit = $r24_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Progressoes                           nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r24_anousu."-".$r24_mesusu."-".$r24_regime."-".$r24_padrao."-".$r24_meses."-".$r24_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Progressoes                           nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r24_anousu."-".$r24_mesusu."-".$r24_regime."-".$r24_padrao."-".$r24_meses."-".$r24_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r24_anousu."-".$r24_mesusu."-".$r24_regime."-".$r24_padrao."-".$r24_meses."-".$r24_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:progress";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r24_anousu,$this->r24_mesusu,$this->r24_regime,$this->r24_padrao,$this->r24_meses);
   }
   function sql_query ( $r24_anousu=null,$r24_mesusu=null,$r24_regime=null,$r24_padrao=null,$r24_meses=null,$r24_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progress ";
     $sql .= "      inner join db_config  on  db_config.codigo = progress.r24_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r24_anousu!=null ){
         $sql2 .= " where progress.r24_anousu = $r24_anousu "; 
       } 
       if($r24_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_mesusu = $r24_mesusu "; 
       } 
       if($r24_regime!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_regime = $r24_regime "; 
       } 
       if($r24_padrao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_padrao = '$r24_padrao' "; 
       } 
       if($r24_meses!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_meses = $r24_meses "; 
       } 
       if($r24_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_instit = $r24_instit "; 
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
   function sql_query_file ( $r24_anousu=null,$r24_mesusu=null,$r24_regime=null,$r24_padrao=null,$r24_meses=null,$r24_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progress ";
     $sql2 = "";
     if($dbwhere==""){
       if($r24_anousu!=null ){
         $sql2 .= " where progress.r24_anousu = $r24_anousu "; 
       } 
       if($r24_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_mesusu = $r24_mesusu "; 
       } 
       if($r24_regime!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_regime = $r24_regime "; 
       } 
       if($r24_padrao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_padrao = '$r24_padrao' "; 
       } 
       if($r24_meses!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_meses = $r24_meses "; 
       } 
       if($r24_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_instit = $r24_instit "; 
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
   function sql_query_padrao ( $r24_anousu=null,$r24_mesusu=null,$r24_regime=null,$r24_padrao=null,$r24_meses=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progress ";
     $sql .= "      inner join padroes on padroes.r02_anousu = progress.r24_anousu 
                                      and padroes.r02_mesusu = progress.r24_mesusu 
				                              and padroes.r02_regime = progress.r24_regime
                         				      and padroes.r02_codigo = progress.r24_padrao
																			and padroes.r02_instit = progress.r24_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r24_anousu!=null ){
         $sql2 .= " where progress.r24_anousu = $r24_anousu "; 
       } 
       if($r24_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_mesusu = $r24_mesusu "; 
       } 
       if($r24_regime!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_regime = $r24_regime "; 
       } 
       if($r24_padrao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_padrao = '$r24_padrao' "; 
       } 
       if($r24_meses!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " progress.r24_meses = $r24_meses "; 
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