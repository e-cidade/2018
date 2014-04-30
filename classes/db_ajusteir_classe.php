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
//CLASSE DA ENTIDADE ajusteir
class cl_ajusteir { 
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
   var $r61_anousu = 0; 
   var $r61_mesusu = 0; 
   var $r61_numcgm = 0; 
   var $r61_rubric = null; 
   var $r61_regist = 0; 
   var $r61_folha = null; 
   var $r61_base = 0; 
   var $r61_depend = 0; 
   var $r61_deduc = 0; 
   var $r61_didade = 0; 
   var $r61_dprev = 0; 
   var $r61_descir = 0; 
   var $r61_percir = 0; 
   var $r61_novod = 0; 
   var $r61_novop = 0; 
   var $r61_altera = 'f'; 
   var $r61_ajuste = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r61_anousu = int4 = Ano do Exercicio 
                 r61_mesusu = int4 = Mes do Exercicio 
                 r61_numcgm = int4 = Numero CGM 
                 r61_rubric = char(4) = Rubrica da base 
                 r61_regist = int4 = Codigo do Funcionario 
                 r61_folha = varchar(1) = Tipo de Folha 
                 r61_base = float8 = valor da base 
                 r61_depend = float8 = valor deducao dependentes ir 
                 r61_deduc = float8 = valor de deducoes ir 
                 r61_didade = float8 = valor deducao p/idade +65 
                 r61_dprev = float8 = deducao total de previdencia 
                 r61_descir = float8 = desconto original de ir 
                 r61_percir = float8 = perc.original do desconto 
                 r61_novod = float8 = novo valor desconto ir 
                 r61_novop = float8 = novo percent.desconto de ir 
                 r61_altera = boolean = informa de deve alterar descon 
                 r61_ajuste = boolean = informa se e do mesmo numcgm 
                 ";
   //funcao construtor da classe 
   function cl_ajusteir() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ajusteir"); 
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
       $this->r61_anousu = ($this->r61_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_anousu"]:$this->r61_anousu);
       $this->r61_mesusu = ($this->r61_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_mesusu"]:$this->r61_mesusu);
       $this->r61_numcgm = ($this->r61_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_numcgm"]:$this->r61_numcgm);
       $this->r61_rubric = ($this->r61_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_rubric"]:$this->r61_rubric);
       $this->r61_regist = ($this->r61_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_regist"]:$this->r61_regist);
       $this->r61_folha = ($this->r61_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_folha"]:$this->r61_folha);
       $this->r61_base = ($this->r61_base == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_base"]:$this->r61_base);
       $this->r61_depend = ($this->r61_depend == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_depend"]:$this->r61_depend);
       $this->r61_deduc = ($this->r61_deduc == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_deduc"]:$this->r61_deduc);
       $this->r61_didade = ($this->r61_didade == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_didade"]:$this->r61_didade);
       $this->r61_dprev = ($this->r61_dprev == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_dprev"]:$this->r61_dprev);
       $this->r61_descir = ($this->r61_descir == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_descir"]:$this->r61_descir);
       $this->r61_percir = ($this->r61_percir == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_percir"]:$this->r61_percir);
       $this->r61_novod = ($this->r61_novod == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_novod"]:$this->r61_novod);
       $this->r61_novop = ($this->r61_novop == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_novop"]:$this->r61_novop);
       $this->r61_altera = ($this->r61_altera == "f"?@$GLOBALS["HTTP_POST_VARS"]["r61_altera"]:$this->r61_altera);
       $this->r61_ajuste = ($this->r61_ajuste == "f"?@$GLOBALS["HTTP_POST_VARS"]["r61_ajuste"]:$this->r61_ajuste);
     }else{
       $this->r61_anousu = ($this->r61_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_anousu"]:$this->r61_anousu);
       $this->r61_mesusu = ($this->r61_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_mesusu"]:$this->r61_mesusu);
       $this->r61_numcgm = ($this->r61_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_numcgm"]:$this->r61_numcgm);
       $this->r61_rubric = ($this->r61_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_rubric"]:$this->r61_rubric);
       $this->r61_regist = ($this->r61_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_regist"]:$this->r61_regist);
       $this->r61_folha = ($this->r61_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["r61_folha"]:$this->r61_folha);
     }
   }
   // funcao para inclusao
   function incluir ($r61_anousu,$r61_mesusu,$r61_numcgm,$r61_rubric,$r61_regist,$r61_folha){ 
      $this->atualizacampos();
     if($this->r61_base == null ){ 
       $this->erro_sql = " Campo valor da base nao Informado.";
       $this->erro_campo = "r61_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_depend == null ){ 
       $this->erro_sql = " Campo valor deducao dependentes ir nao Informado.";
       $this->erro_campo = "r61_depend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_deduc == null ){ 
       $this->erro_sql = " Campo valor de deducoes ir nao Informado.";
       $this->erro_campo = "r61_deduc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_didade == null ){ 
       $this->erro_sql = " Campo valor deducao p/idade +65 nao Informado.";
       $this->erro_campo = "r61_didade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_dprev == null ){ 
       $this->erro_sql = " Campo deducao total de previdencia nao Informado.";
       $this->erro_campo = "r61_dprev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_descir == null ){ 
       $this->erro_sql = " Campo desconto original de ir nao Informado.";
       $this->erro_campo = "r61_descir";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_percir == null ){ 
       $this->erro_sql = " Campo perc.original do desconto nao Informado.";
       $this->erro_campo = "r61_percir";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_novod == null ){ 
       $this->erro_sql = " Campo novo valor desconto ir nao Informado.";
       $this->erro_campo = "r61_novod";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_novop == null ){ 
       $this->erro_sql = " Campo novo percent.desconto de ir nao Informado.";
       $this->erro_campo = "r61_novop";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_altera == null ){ 
       $this->erro_sql = " Campo informa de deve alterar descon nao Informado.";
       $this->erro_campo = "r61_altera";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r61_ajuste == null ){ 
       $this->erro_sql = " Campo informa se e do mesmo numcgm nao Informado.";
       $this->erro_campo = "r61_ajuste";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r61_anousu = $r61_anousu; 
       $this->r61_mesusu = $r61_mesusu; 
       $this->r61_numcgm = $r61_numcgm; 
       $this->r61_rubric = $r61_rubric; 
       $this->r61_regist = $r61_regist; 
       $this->r61_folha = $r61_folha; 
     if(($this->r61_anousu == null) || ($this->r61_anousu == "") ){ 
       $this->erro_sql = " Campo r61_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r61_mesusu == null) || ($this->r61_mesusu == "") ){ 
       $this->erro_sql = " Campo r61_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r61_numcgm == null) || ($this->r61_numcgm == "") ){ 
       $this->erro_sql = " Campo r61_numcgm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r61_rubric == null) || ($this->r61_rubric == "") ){ 
       $this->erro_sql = " Campo r61_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r61_regist == null) || ($this->r61_regist == "") ){ 
       $this->erro_sql = " Campo r61_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r61_folha == null) || ($this->r61_folha == "") ){ 
       $this->erro_sql = " Campo r61_folha nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ajusteir(
                                       r61_anousu 
                                      ,r61_mesusu 
                                      ,r61_numcgm 
                                      ,r61_rubric 
                                      ,r61_regist 
                                      ,r61_folha 
                                      ,r61_base 
                                      ,r61_depend 
                                      ,r61_deduc 
                                      ,r61_didade 
                                      ,r61_dprev 
                                      ,r61_descir 
                                      ,r61_percir 
                                      ,r61_novod 
                                      ,r61_novop 
                                      ,r61_altera 
                                      ,r61_ajuste 
                       )
                values (
                                $this->r61_anousu 
                               ,$this->r61_mesusu 
                               ,$this->r61_numcgm 
                               ,'$this->r61_rubric' 
                               ,$this->r61_regist 
                               ,'$this->r61_folha' 
                               ,$this->r61_base 
                               ,$this->r61_depend 
                               ,$this->r61_deduc 
                               ,$this->r61_didade 
                               ,$this->r61_dprev 
                               ,$this->r61_descir 
                               ,$this->r61_percir 
                               ,$this->r61_novod 
                               ,$this->r61_novop 
                               ,'$this->r61_altera' 
                               ,'$this->r61_ajuste' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "contem as bases de ir de funcionarios com mais de  ($this->r61_anousu."-".$this->r61_mesusu."-".$this->r61_numcgm."-".$this->r61_rubric."-".$this->r61_regist."-".$this->r61_folha) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "contem as bases de ir de funcionarios com mais de  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "contem as bases de ir de funcionarios com mais de  ($this->r61_anousu."-".$this->r61_mesusu."-".$this->r61_numcgm."-".$this->r61_rubric."-".$this->r61_regist."-".$this->r61_folha) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r61_anousu."-".$this->r61_mesusu."-".$this->r61_numcgm."-".$this->r61_rubric."-".$this->r61_regist."-".$this->r61_folha;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r61_anousu,$this->r61_mesusu,$this->r61_numcgm,$this->r61_rubric,$this->r61_regist,$this->r61_folha));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3641,'$this->r61_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3642,'$this->r61_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3643,'$this->r61_numcgm','I')");
       $resac = db_query("insert into db_acountkey values($acount,3644,'$this->r61_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,3645,'$this->r61_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,3646,'$this->r61_folha','I')");
       $resac = db_query("insert into db_acount values($acount,526,3641,'','".AddSlashes(pg_result($resaco,0,'r61_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3642,'','".AddSlashes(pg_result($resaco,0,'r61_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3643,'','".AddSlashes(pg_result($resaco,0,'r61_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3644,'','".AddSlashes(pg_result($resaco,0,'r61_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3645,'','".AddSlashes(pg_result($resaco,0,'r61_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3646,'','".AddSlashes(pg_result($resaco,0,'r61_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3647,'','".AddSlashes(pg_result($resaco,0,'r61_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3648,'','".AddSlashes(pg_result($resaco,0,'r61_depend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3649,'','".AddSlashes(pg_result($resaco,0,'r61_deduc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3650,'','".AddSlashes(pg_result($resaco,0,'r61_didade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3651,'','".AddSlashes(pg_result($resaco,0,'r61_dprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3652,'','".AddSlashes(pg_result($resaco,0,'r61_descir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3653,'','".AddSlashes(pg_result($resaco,0,'r61_percir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3654,'','".AddSlashes(pg_result($resaco,0,'r61_novod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3655,'','".AddSlashes(pg_result($resaco,0,'r61_novop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3656,'','".AddSlashes(pg_result($resaco,0,'r61_altera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,526,3657,'','".AddSlashes(pg_result($resaco,0,'r61_ajuste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r61_anousu=null,$r61_mesusu=null,$r61_numcgm=null,$r61_rubric=null,$r61_regist=null,$r61_folha=null) { 
      $this->atualizacampos();
     $sql = " update ajusteir set ";
     $virgula = "";
     if(trim($this->r61_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_anousu"])){ 
       $sql  .= $virgula." r61_anousu = $this->r61_anousu ";
       $virgula = ",";
       if(trim($this->r61_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r61_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_mesusu"])){ 
       $sql  .= $virgula." r61_mesusu = $this->r61_mesusu ";
       $virgula = ",";
       if(trim($this->r61_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r61_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_numcgm"])){ 
       $sql  .= $virgula." r61_numcgm = $this->r61_numcgm ";
       $virgula = ",";
       if(trim($this->r61_numcgm) == null ){ 
         $this->erro_sql = " Campo Numero CGM nao Informado.";
         $this->erro_campo = "r61_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_rubric"])){ 
       $sql  .= $virgula." r61_rubric = '$this->r61_rubric' ";
       $virgula = ",";
       if(trim($this->r61_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica da base nao Informado.";
         $this->erro_campo = "r61_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_regist"])){ 
       $sql  .= $virgula." r61_regist = $this->r61_regist ";
       $virgula = ",";
       if(trim($this->r61_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r61_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_folha"])){ 
       $sql  .= $virgula." r61_folha = '$this->r61_folha' ";
       $virgula = ",";
       if(trim($this->r61_folha) == null ){ 
         $this->erro_sql = " Campo Tipo de Folha nao Informado.";
         $this->erro_campo = "r61_folha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_base"])){ 
       $sql  .= $virgula." r61_base = $this->r61_base ";
       $virgula = ",";
       if(trim($this->r61_base) == null ){ 
         $this->erro_sql = " Campo valor da base nao Informado.";
         $this->erro_campo = "r61_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_depend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_depend"])){ 
       $sql  .= $virgula." r61_depend = $this->r61_depend ";
       $virgula = ",";
       if(trim($this->r61_depend) == null ){ 
         $this->erro_sql = " Campo valor deducao dependentes ir nao Informado.";
         $this->erro_campo = "r61_depend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_deduc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_deduc"])){ 
       $sql  .= $virgula." r61_deduc = $this->r61_deduc ";
       $virgula = ",";
       if(trim($this->r61_deduc) == null ){ 
         $this->erro_sql = " Campo valor de deducoes ir nao Informado.";
         $this->erro_campo = "r61_deduc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_didade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_didade"])){ 
       $sql  .= $virgula." r61_didade = $this->r61_didade ";
       $virgula = ",";
       if(trim($this->r61_didade) == null ){ 
         $this->erro_sql = " Campo valor deducao p/idade +65 nao Informado.";
         $this->erro_campo = "r61_didade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_dprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_dprev"])){ 
       $sql  .= $virgula." r61_dprev = $this->r61_dprev ";
       $virgula = ",";
       if(trim($this->r61_dprev) == null ){ 
         $this->erro_sql = " Campo deducao total de previdencia nao Informado.";
         $this->erro_campo = "r61_dprev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_descir)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_descir"])){ 
       $sql  .= $virgula." r61_descir = $this->r61_descir ";
       $virgula = ",";
       if(trim($this->r61_descir) == null ){ 
         $this->erro_sql = " Campo desconto original de ir nao Informado.";
         $this->erro_campo = "r61_descir";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_percir)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_percir"])){ 
       $sql  .= $virgula." r61_percir = $this->r61_percir ";
       $virgula = ",";
       if(trim($this->r61_percir) == null ){ 
         $this->erro_sql = " Campo perc.original do desconto nao Informado.";
         $this->erro_campo = "r61_percir";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_novod)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_novod"])){ 
       $sql  .= $virgula." r61_novod = $this->r61_novod ";
       $virgula = ",";
       if(trim($this->r61_novod) == null ){ 
         $this->erro_sql = " Campo novo valor desconto ir nao Informado.";
         $this->erro_campo = "r61_novod";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_novop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_novop"])){ 
       $sql  .= $virgula." r61_novop = $this->r61_novop ";
       $virgula = ",";
       if(trim($this->r61_novop) == null ){ 
         $this->erro_sql = " Campo novo percent.desconto de ir nao Informado.";
         $this->erro_campo = "r61_novop";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_altera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_altera"])){ 
       $sql  .= $virgula." r61_altera = '$this->r61_altera' ";
       $virgula = ",";
       if(trim($this->r61_altera) == null ){ 
         $this->erro_sql = " Campo informa de deve alterar descon nao Informado.";
         $this->erro_campo = "r61_altera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r61_ajuste)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r61_ajuste"])){ 
       $sql  .= $virgula." r61_ajuste = '$this->r61_ajuste' ";
       $virgula = ",";
       if(trim($this->r61_ajuste) == null ){ 
         $this->erro_sql = " Campo informa se e do mesmo numcgm nao Informado.";
         $this->erro_campo = "r61_ajuste";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r61_anousu!=null){
       $sql .= " r61_anousu = $this->r61_anousu";
     }
     if($r61_mesusu!=null){
       $sql .= " and  r61_mesusu = $this->r61_mesusu";
     }
     if($r61_numcgm!=null){
       $sql .= " and  r61_numcgm = $this->r61_numcgm";
     }
     if($r61_rubric!=null){
       $sql .= " and  r61_rubric = '$this->r61_rubric'";
     }
     if($r61_regist!=null){
       $sql .= " and  r61_regist = $this->r61_regist";
     }
     if($r61_folha!=null){
       $sql .= " and  r61_folha = '$this->r61_folha'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r61_anousu,$this->r61_mesusu,$this->r61_numcgm,$this->r61_rubric,$this->r61_regist,$this->r61_folha));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3641,'$this->r61_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3642,'$this->r61_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3643,'$this->r61_numcgm','A')");
         $resac = db_query("insert into db_acountkey values($acount,3644,'$this->r61_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,3645,'$this->r61_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,3646,'$this->r61_folha','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_anousu"]) || $this->r61_anousu != "")
           $resac = db_query("insert into db_acount values($acount,526,3641,'".AddSlashes(pg_result($resaco,$conresaco,'r61_anousu'))."','$this->r61_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_mesusu"]) || $this->r61_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,526,3642,'".AddSlashes(pg_result($resaco,$conresaco,'r61_mesusu'))."','$this->r61_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_numcgm"]) || $this->r61_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,526,3643,'".AddSlashes(pg_result($resaco,$conresaco,'r61_numcgm'))."','$this->r61_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_rubric"]) || $this->r61_rubric != "")
           $resac = db_query("insert into db_acount values($acount,526,3644,'".AddSlashes(pg_result($resaco,$conresaco,'r61_rubric'))."','$this->r61_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_regist"]) || $this->r61_regist != "")
           $resac = db_query("insert into db_acount values($acount,526,3645,'".AddSlashes(pg_result($resaco,$conresaco,'r61_regist'))."','$this->r61_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_folha"]) || $this->r61_folha != "")
           $resac = db_query("insert into db_acount values($acount,526,3646,'".AddSlashes(pg_result($resaco,$conresaco,'r61_folha'))."','$this->r61_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_base"]) || $this->r61_base != "")
           $resac = db_query("insert into db_acount values($acount,526,3647,'".AddSlashes(pg_result($resaco,$conresaco,'r61_base'))."','$this->r61_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_depend"]) || $this->r61_depend != "")
           $resac = db_query("insert into db_acount values($acount,526,3648,'".AddSlashes(pg_result($resaco,$conresaco,'r61_depend'))."','$this->r61_depend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_deduc"]) || $this->r61_deduc != "")
           $resac = db_query("insert into db_acount values($acount,526,3649,'".AddSlashes(pg_result($resaco,$conresaco,'r61_deduc'))."','$this->r61_deduc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_didade"]) || $this->r61_didade != "")
           $resac = db_query("insert into db_acount values($acount,526,3650,'".AddSlashes(pg_result($resaco,$conresaco,'r61_didade'))."','$this->r61_didade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_dprev"]) || $this->r61_dprev != "")
           $resac = db_query("insert into db_acount values($acount,526,3651,'".AddSlashes(pg_result($resaco,$conresaco,'r61_dprev'))."','$this->r61_dprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_descir"]) || $this->r61_descir != "")
           $resac = db_query("insert into db_acount values($acount,526,3652,'".AddSlashes(pg_result($resaco,$conresaco,'r61_descir'))."','$this->r61_descir',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_percir"]) || $this->r61_percir != "")
           $resac = db_query("insert into db_acount values($acount,526,3653,'".AddSlashes(pg_result($resaco,$conresaco,'r61_percir'))."','$this->r61_percir',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_novod"]) || $this->r61_novod != "")
           $resac = db_query("insert into db_acount values($acount,526,3654,'".AddSlashes(pg_result($resaco,$conresaco,'r61_novod'))."','$this->r61_novod',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_novop"]) || $this->r61_novop != "")
           $resac = db_query("insert into db_acount values($acount,526,3655,'".AddSlashes(pg_result($resaco,$conresaco,'r61_novop'))."','$this->r61_novop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_altera"]) || $this->r61_altera != "")
           $resac = db_query("insert into db_acount values($acount,526,3656,'".AddSlashes(pg_result($resaco,$conresaco,'r61_altera'))."','$this->r61_altera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r61_ajuste"]) || $this->r61_ajuste != "")
           $resac = db_query("insert into db_acount values($acount,526,3657,'".AddSlashes(pg_result($resaco,$conresaco,'r61_ajuste'))."','$this->r61_ajuste',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "contem as bases de ir de funcionarios com mais de  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r61_anousu."-".$this->r61_mesusu."-".$this->r61_numcgm."-".$this->r61_rubric."-".$this->r61_regist."-".$this->r61_folha;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "contem as bases de ir de funcionarios com mais de  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r61_anousu."-".$this->r61_mesusu."-".$this->r61_numcgm."-".$this->r61_rubric."-".$this->r61_regist."-".$this->r61_folha;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r61_anousu."-".$this->r61_mesusu."-".$this->r61_numcgm."-".$this->r61_rubric."-".$this->r61_regist."-".$this->r61_folha;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r61_anousu=null,$r61_mesusu=null,$r61_numcgm=null,$r61_rubric=null,$r61_regist=null,$r61_folha=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r61_anousu,$r61_mesusu,$r61_numcgm,$r61_rubric,$r61_regist,$r61_folha));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3641,'$r61_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3642,'$r61_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3643,'$r61_numcgm','E')");
         $resac = db_query("insert into db_acountkey values($acount,3644,'$r61_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,3645,'$r61_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,3646,'$r61_folha','E')");
         $resac = db_query("insert into db_acount values($acount,526,3641,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3642,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3643,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3644,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3645,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3646,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3647,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3648,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_depend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3649,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_deduc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3650,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_didade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3651,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_dprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3652,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_descir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3653,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_percir'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3654,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_novod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3655,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_novop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3656,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_altera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,526,3657,'','".AddSlashes(pg_result($resaco,$iresaco,'r61_ajuste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ajusteir
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r61_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r61_anousu = $r61_anousu ";
        }
        if($r61_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r61_mesusu = $r61_mesusu ";
        }
        if($r61_numcgm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r61_numcgm = $r61_numcgm ";
        }
        if($r61_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r61_rubric = '$r61_rubric' ";
        }
        if($r61_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r61_regist = $r61_regist ";
        }
        if($r61_folha != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r61_folha = '$r61_folha' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "contem as bases de ir de funcionarios com mais de  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r61_anousu."-".$r61_mesusu."-".$r61_numcgm."-".$r61_rubric."-".$r61_regist."-".$r61_folha;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "contem as bases de ir de funcionarios com mais de  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r61_anousu."-".$r61_mesusu."-".$r61_numcgm."-".$r61_rubric."-".$r61_regist."-".$r61_folha;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r61_anousu."-".$r61_mesusu."-".$r61_numcgm."-".$r61_rubric."-".$r61_regist."-".$r61_folha;
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
        $this->erro_sql   = "Record Vazio na Tabela:ajusteir";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r61_anousu=null,$r61_mesusu=null,$r61_numcgm=null,$r61_rubric=null,$r61_regist=null,$r61_folha=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ajusteir ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = ajusteir.r61_numcgm";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = ajusteir.r61_anousu and  pessoal.r01_mesusu = ajusteir.r61_mesusu and  pessoal.r01_regist = ajusteir.r61_regist";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = ajusteir.r61_anousu and  rubricas.r06_mesusu = ajusteir.r61_mesusu and  rubricas.r06_codigo = ajusteir.r61_rubric";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      left  join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join db_config  as a on   a.codigo = rubricas.r06_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($r61_anousu!=null ){
         $sql2 .= " where ajusteir.r61_anousu = $r61_anousu "; 
       } 
       if($r61_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_mesusu = $r61_mesusu "; 
       } 
       if($r61_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_numcgm = $r61_numcgm "; 
       } 
       if($r61_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_rubric = '$r61_rubric' "; 
       } 
       if($r61_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_regist = $r61_regist "; 
       } 
       if($r61_folha!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_folha = '$r61_folha' "; 
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
   function sql_query_file ( $r61_anousu=null,$r61_mesusu=null,$r61_numcgm=null,$r61_rubric=null,$r61_regist=null,$r61_folha=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ajusteir ";
     $sql2 = "";
     if($dbwhere==""){
       if($r61_anousu!=null ){
         $sql2 .= " where ajusteir.r61_anousu = $r61_anousu "; 
       } 
       if($r61_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_mesusu = $r61_mesusu "; 
       } 
       if($r61_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_numcgm = $r61_numcgm "; 
       } 
       if($r61_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_rubric = '$r61_rubric' "; 
       } 
       if($r61_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_regist = $r61_regist "; 
       } 
       if($r61_folha!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ajusteir.r61_folha = '$r61_folha' "; 
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