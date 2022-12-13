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

//MODULO: prefeitura
//CLASSE DA ENTIDADE configdbpref
class cl_configdbpref { 
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
   var $w13_liberaatucgm = 'f'; 
   var $w13_liberapedsenha = 'f'; 
   var $w13_permfornsemlog = 'f'; 
   var $w13_permvarsemlog = 'f'; 
   var $w13_liberaescritorios = 0; 
   var $w13_liberaimobiliaria = 'f'; 
   var $w13_permconscgm = 'f'; 
   var $w13_aliqissretido = 'f'; 
   var $w13_liberaissretido = 'f'; 
   var $w13_utilizafolha = 'f'; 
   var $w13_instit = 0; 
   var $w13_libcertpos = 'f'; 
   var $w13_libcarnevariavel = 'f'; 
   var $w13_libsociosdai = 'f'; 
   var $w13_libissprestado = 'f'; 
   var $w13_emailadmin = null; 
   var $w13_liberalancisssemmov = 'f'; 
   var $w13_exigecpfcnpjmatricula = 'f'; 
   var $w13_exigecpfcnpjinscricao = 'f'; 
   var $w13_regracnd = 0; 
   var $w13_permconsservdemit = 'f'; 
   var $w13_tipocertidao = 0; 
   var $w13_agrupadebrecibos = 'f'; 
   var $w13_msgaviso = 'f'; 
   var $w13_tipocodigocertidao = 0; 
   var $w13_uploadarquivos = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w13_liberaatucgm = bool = Libera Atualização do CGM 
                 w13_liberapedsenha = bool = Libera Pedido de Senha 
                 w13_permfornsemlog = bool = permite acessar fornecedor sem estar logado 
                 w13_permvarsemlog = bool = Permite ISS Var sem login 
                 w13_liberaescritorios = int4 = Regra para Escritório Informar Clientes 
                 w13_liberaimobiliaria = bool = Libera Imobiliárias para adicionar seus clientes 
                 w13_permconscgm = bool = Permite consulta Contribuinte por CGM 
                 w13_aliqissretido = bool = Permitir alíquota fora do padrão 
                 w13_liberaissretido = bool = Libera ISS Retido sem login 
                 w13_utilizafolha = bool = Utiliza Folha 
                 w13_instit = int4 = instituição 
                 w13_libcertpos = bool = Libera certidao positiva 
                 w13_libcarnevariavel = bool = Libera carne de ISSQN variável 
                 w13_libsociosdai = bool = Libera aba socios na DAI 
                 w13_libissprestado = bool = Libera opcao de ISSQN prestado 
                 w13_emailadmin = varchar(50) = E-mail do administrador 
                 w13_liberalancisssemmov = bool = Permitir ISSQN sem movimento 
                 w13_exigecpfcnpjmatricula = bool = Exige CPF/CNPJ na consulta de imóveis 
                 w13_exigecpfcnpjinscricao = bool = Exige CPF/CNPJ na consulta de inscrições 
                 w13_regracnd = int4 = Regra para Emissão CND 
                 w13_permconsservdemit = bool = Permite Consulta de Servidor Demitido 
                 w13_tipocertidao = int4 = Forma Emissão Certidão de Débitos 
                 w13_agrupadebrecibos = bool = Agrupa Déb. Venc. na Emissão de Recibos 
                 w13_msgaviso = bool = Mostrar Mensagem de Aviso de Corte 
                 w13_tipocodigocertidao = int4 = Tipo de Codificação da certidão 
                 w13_uploadarquivos = text = Caminho da Pasta de Upload de Arquivos 
                 ";
   //funcao construtor da classe 
   function cl_configdbpref() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("configdbpref"); 
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
       $this->w13_liberaatucgm = ($this->w13_liberaatucgm == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_liberaatucgm"]:$this->w13_liberaatucgm);
       $this->w13_liberapedsenha = ($this->w13_liberapedsenha == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_liberapedsenha"]:$this->w13_liberapedsenha);
       $this->w13_permfornsemlog = ($this->w13_permfornsemlog == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_permfornsemlog"]:$this->w13_permfornsemlog);
       $this->w13_permvarsemlog = ($this->w13_permvarsemlog == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_permvarsemlog"]:$this->w13_permvarsemlog);
       $this->w13_liberaescritorios = ($this->w13_liberaescritorios == ""?@$GLOBALS["HTTP_POST_VARS"]["w13_liberaescritorios"]:$this->w13_liberaescritorios);
       $this->w13_liberaimobiliaria = ($this->w13_liberaimobiliaria == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_liberaimobiliaria"]:$this->w13_liberaimobiliaria);
       $this->w13_permconscgm = ($this->w13_permconscgm == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_permconscgm"]:$this->w13_permconscgm);
       $this->w13_aliqissretido = ($this->w13_aliqissretido == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_aliqissretido"]:$this->w13_aliqissretido);
       $this->w13_liberaissretido = ($this->w13_liberaissretido == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_liberaissretido"]:$this->w13_liberaissretido);
       $this->w13_utilizafolha = ($this->w13_utilizafolha == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_utilizafolha"]:$this->w13_utilizafolha);
       $this->w13_instit = ($this->w13_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["w13_instit"]:$this->w13_instit);
       $this->w13_libcertpos = ($this->w13_libcertpos == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_libcertpos"]:$this->w13_libcertpos);
       $this->w13_libcarnevariavel = ($this->w13_libcarnevariavel == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_libcarnevariavel"]:$this->w13_libcarnevariavel);
       $this->w13_libsociosdai = ($this->w13_libsociosdai == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_libsociosdai"]:$this->w13_libsociosdai);
       $this->w13_libissprestado = ($this->w13_libissprestado == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_libissprestado"]:$this->w13_libissprestado);
       $this->w13_emailadmin = ($this->w13_emailadmin == ""?@$GLOBALS["HTTP_POST_VARS"]["w13_emailadmin"]:$this->w13_emailadmin);
       $this->w13_liberalancisssemmov = ($this->w13_liberalancisssemmov == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_liberalancisssemmov"]:$this->w13_liberalancisssemmov);
       $this->w13_exigecpfcnpjmatricula = ($this->w13_exigecpfcnpjmatricula == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_exigecpfcnpjmatricula"]:$this->w13_exigecpfcnpjmatricula);
       $this->w13_exigecpfcnpjinscricao = ($this->w13_exigecpfcnpjinscricao == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_exigecpfcnpjinscricao"]:$this->w13_exigecpfcnpjinscricao);
       $this->w13_regracnd = ($this->w13_regracnd == ""?@$GLOBALS["HTTP_POST_VARS"]["w13_regracnd"]:$this->w13_regracnd);
       $this->w13_permconsservdemit = ($this->w13_permconsservdemit == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_permconsservdemit"]:$this->w13_permconsservdemit);
       $this->w13_tipocertidao = ($this->w13_tipocertidao == ""?@$GLOBALS["HTTP_POST_VARS"]["w13_tipocertidao"]:$this->w13_tipocertidao);
       $this->w13_agrupadebrecibos = ($this->w13_agrupadebrecibos == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_agrupadebrecibos"]:$this->w13_agrupadebrecibos);
       $this->w13_msgaviso = ($this->w13_msgaviso == "f"?@$GLOBALS["HTTP_POST_VARS"]["w13_msgaviso"]:$this->w13_msgaviso);
       $this->w13_tipocodigocertidao = ($this->w13_tipocodigocertidao == ""?@$GLOBALS["HTTP_POST_VARS"]["w13_tipocodigocertidao"]:$this->w13_tipocodigocertidao);
       $this->w13_uploadarquivos = ($this->w13_uploadarquivos == ""?@$GLOBALS["HTTP_POST_VARS"]["w13_uploadarquivos"]:$this->w13_uploadarquivos);
     }else{
       $this->w13_instit = ($this->w13_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["w13_instit"]:$this->w13_instit);
     }
   }
   // funcao para inclusao
   function incluir ($w13_instit){ 
      $this->atualizacampos();
     if($this->w13_liberaatucgm == null ){ 
       $this->erro_sql = " Campo Libera Atualização do CGM não informado.";
       $this->erro_campo = "w13_liberaatucgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_liberapedsenha == null ){ 
       $this->erro_sql = " Campo Libera Pedido de Senha não informado.";
       $this->erro_campo = "w13_liberapedsenha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_permfornsemlog == null ){ 
       $this->erro_sql = " Campo permite acessar fornecedor sem estar logado não informado.";
       $this->erro_campo = "w13_permfornsemlog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_permvarsemlog == null ){ 
       $this->erro_sql = " Campo Permite ISS Var sem login não informado.";
       $this->erro_campo = "w13_permvarsemlog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_liberaescritorios == null ){ 
       $this->erro_sql = " Campo Regra para Escritório Informar Clientes não informado.";
       $this->erro_campo = "w13_liberaescritorios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_liberaimobiliaria == null ){ 
       $this->erro_sql = " Campo Libera Imobiliárias para adicionar seus clientes não informado.";
       $this->erro_campo = "w13_liberaimobiliaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_permconscgm == null ){ 
       $this->erro_sql = " Campo Permite consulta Contribuinte por CGM não informado.";
       $this->erro_campo = "w13_permconscgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_aliqissretido == null ){ 
       $this->erro_sql = " Campo Permitir alíquota fora do padrão não informado.";
       $this->erro_campo = "w13_aliqissretido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_liberaissretido == null ){ 
       $this->erro_sql = " Campo Libera ISS Retido sem login não informado.";
       $this->erro_campo = "w13_liberaissretido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_utilizafolha == null ){ 
       $this->erro_sql = " Campo Utiliza Folha não informado.";
       $this->erro_campo = "w13_utilizafolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_libcertpos == null ){ 
       $this->erro_sql = " Campo Libera certidao positiva não informado.";
       $this->erro_campo = "w13_libcertpos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_libcarnevariavel == null ){ 
       $this->erro_sql = " Campo Libera carne de ISSQN variável não informado.";
       $this->erro_campo = "w13_libcarnevariavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_libsociosdai == null ){ 
       $this->erro_sql = " Campo Libera aba socios na DAI não informado.";
       $this->erro_campo = "w13_libsociosdai";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_libissprestado == null ){ 
       $this->erro_sql = " Campo Libera opcao de ISSQN prestado não informado.";
       $this->erro_campo = "w13_libissprestado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_emailadmin == null ){ 
       $this->erro_sql = " Campo E-mail do administrador não informado.";
       $this->erro_campo = "w13_emailadmin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_liberalancisssemmov == null ){ 
       $this->erro_sql = " Campo Permitir ISSQN sem movimento não informado.";
       $this->erro_campo = "w13_liberalancisssemmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_exigecpfcnpjmatricula == null ){ 
       $this->erro_sql = " Campo Exige CPF/CNPJ na consulta de imóveis não informado.";
       $this->erro_campo = "w13_exigecpfcnpjmatricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_exigecpfcnpjinscricao == null ){ 
       $this->erro_sql = " Campo Exige CPF/CNPJ na consulta de inscrições não informado.";
       $this->erro_campo = "w13_exigecpfcnpjinscricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_regracnd == null ){ 
       $this->erro_sql = " Campo Regra para Emissão CND não informado.";
       $this->erro_campo = "w13_regracnd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_permconsservdemit == null ){ 
       $this->erro_sql = " Campo Permite Consulta de Servidor Demitido não informado.";
       $this->erro_campo = "w13_permconsservdemit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_tipocertidao == null ){ 
       $this->erro_sql = " Campo Forma Emissão Certidão de Débitos não informado.";
       $this->erro_campo = "w13_tipocertidao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_agrupadebrecibos == null ){ 
       $this->erro_sql = " Campo Agrupa Déb. Venc. na Emissão de Recibos não informado.";
       $this->erro_campo = "w13_agrupadebrecibos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_msgaviso == null ){ 
       $this->erro_sql = " Campo Mostrar Mensagem de Aviso de Corte não informado.";
       $this->erro_campo = "w13_msgaviso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w13_tipocodigocertidao == null ){ 
       $this->erro_sql = " Campo Tipo de Codificação da certidão não informado.";
       $this->erro_campo = "w13_tipocodigocertidao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->w13_instit = $w13_instit; 
     if(($this->w13_instit == null) || ($this->w13_instit == "") ){ 
       $this->erro_sql = " Campo w13_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into configdbpref(
                                       w13_liberaatucgm 
                                      ,w13_liberapedsenha 
                                      ,w13_permfornsemlog 
                                      ,w13_permvarsemlog 
                                      ,w13_liberaescritorios 
                                      ,w13_liberaimobiliaria 
                                      ,w13_permconscgm 
                                      ,w13_aliqissretido 
                                      ,w13_liberaissretido 
                                      ,w13_utilizafolha 
                                      ,w13_instit 
                                      ,w13_libcertpos 
                                      ,w13_libcarnevariavel 
                                      ,w13_libsociosdai 
                                      ,w13_libissprestado 
                                      ,w13_emailadmin 
                                      ,w13_liberalancisssemmov 
                                      ,w13_exigecpfcnpjmatricula 
                                      ,w13_exigecpfcnpjinscricao 
                                      ,w13_regracnd 
                                      ,w13_permconsservdemit 
                                      ,w13_tipocertidao 
                                      ,w13_agrupadebrecibos 
                                      ,w13_msgaviso 
                                      ,w13_tipocodigocertidao 
                                      ,w13_uploadarquivos 
                       )
                values (
                                '$this->w13_liberaatucgm' 
                               ,'$this->w13_liberapedsenha' 
                               ,'$this->w13_permfornsemlog' 
                               ,'$this->w13_permvarsemlog' 
                               ,$this->w13_liberaescritorios 
                               ,'$this->w13_liberaimobiliaria' 
                               ,'$this->w13_permconscgm' 
                               ,'$this->w13_aliqissretido' 
                               ,'$this->w13_liberaissretido' 
                               ,'$this->w13_utilizafolha' 
                               ,$this->w13_instit 
                               ,'$this->w13_libcertpos' 
                               ,'$this->w13_libcarnevariavel' 
                               ,'$this->w13_libsociosdai' 
                               ,'$this->w13_libissprestado' 
                               ,'$this->w13_emailadmin' 
                               ,'$this->w13_liberalancisssemmov' 
                               ,'$this->w13_exigecpfcnpjmatricula' 
                               ,'$this->w13_exigecpfcnpjinscricao' 
                               ,$this->w13_regracnd 
                               ,'$this->w13_permconsservdemit' 
                               ,$this->w13_tipocertidao 
                               ,'$this->w13_agrupadebrecibos' 
                               ,'$this->w13_msgaviso' 
                               ,$this->w13_tipocodigocertidao 
                               ,'$this->w13_uploadarquivos' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "configdbpref ($this->w13_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "configdbpref já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "configdbpref ($this->w13_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w13_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->w13_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9570,'$this->w13_instit','I')");
         $resac = db_query("insert into db_acount values($acount,1383,8213,'','".AddSlashes(pg_result($resaco,0,'w13_liberaatucgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,8214,'','".AddSlashes(pg_result($resaco,0,'w13_liberapedsenha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,8216,'','".AddSlashes(pg_result($resaco,0,'w13_permfornsemlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,8215,'','".AddSlashes(pg_result($resaco,0,'w13_permvarsemlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,8236,'','".AddSlashes(pg_result($resaco,0,'w13_liberaescritorios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,8235,'','".AddSlashes(pg_result($resaco,0,'w13_liberaimobiliaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,8372,'','".AddSlashes(pg_result($resaco,0,'w13_permconscgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,8664,'','".AddSlashes(pg_result($resaco,0,'w13_aliqissretido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,8766,'','".AddSlashes(pg_result($resaco,0,'w13_liberaissretido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,9569,'','".AddSlashes(pg_result($resaco,0,'w13_utilizafolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,9570,'','".AddSlashes(pg_result($resaco,0,'w13_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,10725,'','".AddSlashes(pg_result($resaco,0,'w13_libcertpos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,10726,'','".AddSlashes(pg_result($resaco,0,'w13_libcarnevariavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,10727,'','".AddSlashes(pg_result($resaco,0,'w13_libsociosdai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,10728,'','".AddSlashes(pg_result($resaco,0,'w13_libissprestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,12352,'','".AddSlashes(pg_result($resaco,0,'w13_emailadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,12612,'','".AddSlashes(pg_result($resaco,0,'w13_liberalancisssemmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,13345,'','".AddSlashes(pg_result($resaco,0,'w13_exigecpfcnpjmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,20543,'','".AddSlashes(pg_result($resaco,0,'w13_exigecpfcnpjinscricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,14401,'','".AddSlashes(pg_result($resaco,0,'w13_regracnd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,14548,'','".AddSlashes(pg_result($resaco,0,'w13_permconsservdemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,14585,'','".AddSlashes(pg_result($resaco,0,'w13_tipocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,14593,'','".AddSlashes(pg_result($resaco,0,'w13_agrupadebrecibos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,15337,'','".AddSlashes(pg_result($resaco,0,'w13_msgaviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,19218,'','".AddSlashes(pg_result($resaco,0,'w13_tipocodigocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1383,20004,'','".AddSlashes(pg_result($resaco,0,'w13_uploadarquivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w13_instit=null) { 
      $this->atualizacampos();
     $sql = " update configdbpref set ";
     $virgula = "";
     if(trim($this->w13_liberaatucgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_liberaatucgm"])){ 
       $sql  .= $virgula." w13_liberaatucgm = '$this->w13_liberaatucgm' ";
       $virgula = ",";
       if(trim($this->w13_liberaatucgm) == null ){ 
         $this->erro_sql = " Campo Libera Atualização do CGM não informado.";
         $this->erro_campo = "w13_liberaatucgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_liberapedsenha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_liberapedsenha"])){ 
       $sql  .= $virgula." w13_liberapedsenha = '$this->w13_liberapedsenha' ";
       $virgula = ",";
       if(trim($this->w13_liberapedsenha) == null ){ 
         $this->erro_sql = " Campo Libera Pedido de Senha não informado.";
         $this->erro_campo = "w13_liberapedsenha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_permfornsemlog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_permfornsemlog"])){ 
       $sql  .= $virgula." w13_permfornsemlog = '$this->w13_permfornsemlog' ";
       $virgula = ",";
       if(trim($this->w13_permfornsemlog) == null ){ 
         $this->erro_sql = " Campo permite acessar fornecedor sem estar logado não informado.";
         $this->erro_campo = "w13_permfornsemlog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_permvarsemlog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_permvarsemlog"])){ 
       $sql  .= $virgula." w13_permvarsemlog = '$this->w13_permvarsemlog' ";
       $virgula = ",";
       if(trim($this->w13_permvarsemlog) == null ){ 
         $this->erro_sql = " Campo Permite ISS Var sem login não informado.";
         $this->erro_campo = "w13_permvarsemlog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_liberaescritorios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_liberaescritorios"])){ 
       $sql  .= $virgula." w13_liberaescritorios = $this->w13_liberaescritorios ";
       $virgula = ",";
       if(trim($this->w13_liberaescritorios) == null ){ 
         $this->erro_sql = " Campo Regra para Escritório Informar Clientes não informado.";
         $this->erro_campo = "w13_liberaescritorios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_liberaimobiliaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_liberaimobiliaria"])){ 
       $sql  .= $virgula." w13_liberaimobiliaria = '$this->w13_liberaimobiliaria' ";
       $virgula = ",";
       if(trim($this->w13_liberaimobiliaria) == null ){ 
         $this->erro_sql = " Campo Libera Imobiliárias para adicionar seus clientes não informado.";
         $this->erro_campo = "w13_liberaimobiliaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_permconscgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_permconscgm"])){ 
       $sql  .= $virgula." w13_permconscgm = '$this->w13_permconscgm' ";
       $virgula = ",";
       if(trim($this->w13_permconscgm) == null ){ 
         $this->erro_sql = " Campo Permite consulta Contribuinte por CGM não informado.";
         $this->erro_campo = "w13_permconscgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_aliqissretido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_aliqissretido"])){ 
       $sql  .= $virgula." w13_aliqissretido = '$this->w13_aliqissretido' ";
       $virgula = ",";
       if(trim($this->w13_aliqissretido) == null ){ 
         $this->erro_sql = " Campo Permitir alíquota fora do padrão não informado.";
         $this->erro_campo = "w13_aliqissretido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_liberaissretido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_liberaissretido"])){ 
       $sql  .= $virgula." w13_liberaissretido = '$this->w13_liberaissretido' ";
       $virgula = ",";
       if(trim($this->w13_liberaissretido) == null ){ 
         $this->erro_sql = " Campo Libera ISS Retido sem login não informado.";
         $this->erro_campo = "w13_liberaissretido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_utilizafolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_utilizafolha"])){ 
       $sql  .= $virgula." w13_utilizafolha = '$this->w13_utilizafolha' ";
       $virgula = ",";
       if(trim($this->w13_utilizafolha) == null ){ 
         $this->erro_sql = " Campo Utiliza Folha não informado.";
         $this->erro_campo = "w13_utilizafolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_instit"])){ 
       $sql  .= $virgula." w13_instit = $this->w13_instit ";
       $virgula = ",";
       if(trim($this->w13_instit) == null ){ 
         $this->erro_sql = " Campo instituição não informado.";
         $this->erro_campo = "w13_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_libcertpos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_libcertpos"])){ 
       $sql  .= $virgula." w13_libcertpos = '$this->w13_libcertpos' ";
       $virgula = ",";
       if(trim($this->w13_libcertpos) == null ){ 
         $this->erro_sql = " Campo Libera certidao positiva não informado.";
         $this->erro_campo = "w13_libcertpos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_libcarnevariavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_libcarnevariavel"])){ 
       $sql  .= $virgula." w13_libcarnevariavel = '$this->w13_libcarnevariavel' ";
       $virgula = ",";
       if(trim($this->w13_libcarnevariavel) == null ){ 
         $this->erro_sql = " Campo Libera carne de ISSQN variável não informado.";
         $this->erro_campo = "w13_libcarnevariavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_libsociosdai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_libsociosdai"])){ 
       $sql  .= $virgula." w13_libsociosdai = '$this->w13_libsociosdai' ";
       $virgula = ",";
       if(trim($this->w13_libsociosdai) == null ){ 
         $this->erro_sql = " Campo Libera aba socios na DAI não informado.";
         $this->erro_campo = "w13_libsociosdai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_libissprestado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_libissprestado"])){ 
       $sql  .= $virgula." w13_libissprestado = '$this->w13_libissprestado' ";
       $virgula = ",";
       if(trim($this->w13_libissprestado) == null ){ 
         $this->erro_sql = " Campo Libera opcao de ISSQN prestado não informado.";
         $this->erro_campo = "w13_libissprestado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_emailadmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_emailadmin"])){ 
       $sql  .= $virgula." w13_emailadmin = '$this->w13_emailadmin' ";
       $virgula = ",";
       if(trim($this->w13_emailadmin) == null ){ 
         $this->erro_sql = " Campo E-mail do administrador não informado.";
         $this->erro_campo = "w13_emailadmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_liberalancisssemmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_liberalancisssemmov"])){ 
       $sql  .= $virgula." w13_liberalancisssemmov = '$this->w13_liberalancisssemmov' ";
       $virgula = ",";
       if(trim($this->w13_liberalancisssemmov) == null ){ 
         $this->erro_sql = " Campo Permitir ISSQN sem movimento não informado.";
         $this->erro_campo = "w13_liberalancisssemmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_exigecpfcnpjmatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_exigecpfcnpjmatricula"])){ 
       $sql  .= $virgula." w13_exigecpfcnpjmatricula = '$this->w13_exigecpfcnpjmatricula' ";
       $virgula = ",";
       if(trim($this->w13_exigecpfcnpjmatricula) == null ){ 
         $this->erro_sql = " Campo Exige CPF/CNPJ na consulta de imóveis não informado.";
         $this->erro_campo = "w13_exigecpfcnpjmatricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_exigecpfcnpjinscricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_exigecpfcnpjinscricao"])){ 
       $sql  .= $virgula." w13_exigecpfcnpjinscricao = '$this->w13_exigecpfcnpjinscricao' ";
       $virgula = ",";
       if(trim($this->w13_exigecpfcnpjinscricao) == null ){ 
         $this->erro_sql = " Campo Exige CPF/CNPJ na consulta de inscrições não informado.";
         $this->erro_campo = "w13_exigecpfcnpjinscricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_regracnd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_regracnd"])){ 
       $sql  .= $virgula." w13_regracnd = $this->w13_regracnd ";
       $virgula = ",";
       if(trim($this->w13_regracnd) == null ){ 
         $this->erro_sql = " Campo Regra para Emissão CND não informado.";
         $this->erro_campo = "w13_regracnd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_permconsservdemit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_permconsservdemit"])){ 
       $sql  .= $virgula." w13_permconsservdemit = '$this->w13_permconsservdemit' ";
       $virgula = ",";
       if(trim($this->w13_permconsservdemit) == null ){ 
         $this->erro_sql = " Campo Permite Consulta de Servidor Demitido não informado.";
         $this->erro_campo = "w13_permconsservdemit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_tipocertidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_tipocertidao"])){ 
       $sql  .= $virgula." w13_tipocertidao = $this->w13_tipocertidao ";
       $virgula = ",";
       if(trim($this->w13_tipocertidao) == null ){ 
         $this->erro_sql = " Campo Forma Emissão Certidão de Débitos não informado.";
         $this->erro_campo = "w13_tipocertidao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_agrupadebrecibos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_agrupadebrecibos"])){ 
       $sql  .= $virgula." w13_agrupadebrecibos = '$this->w13_agrupadebrecibos' ";
       $virgula = ",";
       if(trim($this->w13_agrupadebrecibos) == null ){ 
         $this->erro_sql = " Campo Agrupa Déb. Venc. na Emissão de Recibos não informado.";
         $this->erro_campo = "w13_agrupadebrecibos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_msgaviso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_msgaviso"])){ 
       $sql  .= $virgula." w13_msgaviso = '$this->w13_msgaviso' ";
       $virgula = ",";
       if(trim($this->w13_msgaviso) == null ){ 
         $this->erro_sql = " Campo Mostrar Mensagem de Aviso de Corte não informado.";
         $this->erro_campo = "w13_msgaviso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_tipocodigocertidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_tipocodigocertidao"])){ 
       $sql  .= $virgula." w13_tipocodigocertidao = $this->w13_tipocodigocertidao ";
       $virgula = ",";
       if(trim($this->w13_tipocodigocertidao) == null ){ 
         $this->erro_sql = " Campo Tipo de Codificação da certidão não informado.";
         $this->erro_campo = "w13_tipocodigocertidao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w13_uploadarquivos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w13_uploadarquivos"])){ 
       $sql  .= $virgula." w13_uploadarquivos = '$this->w13_uploadarquivos' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($w13_instit!=null){
       $sql .= " w13_instit = $this->w13_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->w13_instit));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,9570,'$this->w13_instit','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_liberaatucgm"]) || $this->w13_liberaatucgm != "")
             $resac = db_query("insert into db_acount values($acount,1383,8213,'".AddSlashes(pg_result($resaco,$conresaco,'w13_liberaatucgm'))."','$this->w13_liberaatucgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_liberapedsenha"]) || $this->w13_liberapedsenha != "")
             $resac = db_query("insert into db_acount values($acount,1383,8214,'".AddSlashes(pg_result($resaco,$conresaco,'w13_liberapedsenha'))."','$this->w13_liberapedsenha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_permfornsemlog"]) || $this->w13_permfornsemlog != "")
             $resac = db_query("insert into db_acount values($acount,1383,8216,'".AddSlashes(pg_result($resaco,$conresaco,'w13_permfornsemlog'))."','$this->w13_permfornsemlog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_permvarsemlog"]) || $this->w13_permvarsemlog != "")
             $resac = db_query("insert into db_acount values($acount,1383,8215,'".AddSlashes(pg_result($resaco,$conresaco,'w13_permvarsemlog'))."','$this->w13_permvarsemlog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_liberaescritorios"]) || $this->w13_liberaescritorios != "")
             $resac = db_query("insert into db_acount values($acount,1383,8236,'".AddSlashes(pg_result($resaco,$conresaco,'w13_liberaescritorios'))."','$this->w13_liberaescritorios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_liberaimobiliaria"]) || $this->w13_liberaimobiliaria != "")
             $resac = db_query("insert into db_acount values($acount,1383,8235,'".AddSlashes(pg_result($resaco,$conresaco,'w13_liberaimobiliaria'))."','$this->w13_liberaimobiliaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_permconscgm"]) || $this->w13_permconscgm != "")
             $resac = db_query("insert into db_acount values($acount,1383,8372,'".AddSlashes(pg_result($resaco,$conresaco,'w13_permconscgm'))."','$this->w13_permconscgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_aliqissretido"]) || $this->w13_aliqissretido != "")
             $resac = db_query("insert into db_acount values($acount,1383,8664,'".AddSlashes(pg_result($resaco,$conresaco,'w13_aliqissretido'))."','$this->w13_aliqissretido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_liberaissretido"]) || $this->w13_liberaissretido != "")
             $resac = db_query("insert into db_acount values($acount,1383,8766,'".AddSlashes(pg_result($resaco,$conresaco,'w13_liberaissretido'))."','$this->w13_liberaissretido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_utilizafolha"]) || $this->w13_utilizafolha != "")
             $resac = db_query("insert into db_acount values($acount,1383,9569,'".AddSlashes(pg_result($resaco,$conresaco,'w13_utilizafolha'))."','$this->w13_utilizafolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_instit"]) || $this->w13_instit != "")
             $resac = db_query("insert into db_acount values($acount,1383,9570,'".AddSlashes(pg_result($resaco,$conresaco,'w13_instit'))."','$this->w13_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_libcertpos"]) || $this->w13_libcertpos != "")
             $resac = db_query("insert into db_acount values($acount,1383,10725,'".AddSlashes(pg_result($resaco,$conresaco,'w13_libcertpos'))."','$this->w13_libcertpos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_libcarnevariavel"]) || $this->w13_libcarnevariavel != "")
             $resac = db_query("insert into db_acount values($acount,1383,10726,'".AddSlashes(pg_result($resaco,$conresaco,'w13_libcarnevariavel'))."','$this->w13_libcarnevariavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_libsociosdai"]) || $this->w13_libsociosdai != "")
             $resac = db_query("insert into db_acount values($acount,1383,10727,'".AddSlashes(pg_result($resaco,$conresaco,'w13_libsociosdai'))."','$this->w13_libsociosdai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_libissprestado"]) || $this->w13_libissprestado != "")
             $resac = db_query("insert into db_acount values($acount,1383,10728,'".AddSlashes(pg_result($resaco,$conresaco,'w13_libissprestado'))."','$this->w13_libissprestado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_emailadmin"]) || $this->w13_emailadmin != "")
             $resac = db_query("insert into db_acount values($acount,1383,12352,'".AddSlashes(pg_result($resaco,$conresaco,'w13_emailadmin'))."','$this->w13_emailadmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_liberalancisssemmov"]) || $this->w13_liberalancisssemmov != "")
             $resac = db_query("insert into db_acount values($acount,1383,12612,'".AddSlashes(pg_result($resaco,$conresaco,'w13_liberalancisssemmov'))."','$this->w13_liberalancisssemmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_exigecpfcnpjmatricula"]) || $this->w13_exigecpfcnpjmatricula != "")
             $resac = db_query("insert into db_acount values($acount,1383,13345,'".AddSlashes(pg_result($resaco,$conresaco,'w13_exigecpfcnpjmatricula'))."','$this->w13_exigecpfcnpjmatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_exigecpfcnpjinscricao"]) || $this->w13_exigecpfcnpjinscricao != "")
             $resac = db_query("insert into db_acount values($acount,1383,20543,'".AddSlashes(pg_result($resaco,$conresaco,'w13_exigecpfcnpjinscricao'))."','$this->w13_exigecpfcnpjinscricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_regracnd"]) || $this->w13_regracnd != "")
             $resac = db_query("insert into db_acount values($acount,1383,14401,'".AddSlashes(pg_result($resaco,$conresaco,'w13_regracnd'))."','$this->w13_regracnd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_permconsservdemit"]) || $this->w13_permconsservdemit != "")
             $resac = db_query("insert into db_acount values($acount,1383,14548,'".AddSlashes(pg_result($resaco,$conresaco,'w13_permconsservdemit'))."','$this->w13_permconsservdemit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_tipocertidao"]) || $this->w13_tipocertidao != "")
             $resac = db_query("insert into db_acount values($acount,1383,14585,'".AddSlashes(pg_result($resaco,$conresaco,'w13_tipocertidao'))."','$this->w13_tipocertidao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_agrupadebrecibos"]) || $this->w13_agrupadebrecibos != "")
             $resac = db_query("insert into db_acount values($acount,1383,14593,'".AddSlashes(pg_result($resaco,$conresaco,'w13_agrupadebrecibos'))."','$this->w13_agrupadebrecibos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_msgaviso"]) || $this->w13_msgaviso != "")
             $resac = db_query("insert into db_acount values($acount,1383,15337,'".AddSlashes(pg_result($resaco,$conresaco,'w13_msgaviso'))."','$this->w13_msgaviso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_tipocodigocertidao"]) || $this->w13_tipocodigocertidao != "")
             $resac = db_query("insert into db_acount values($acount,1383,19218,'".AddSlashes(pg_result($resaco,$conresaco,'w13_tipocodigocertidao'))."','$this->w13_tipocodigocertidao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["w13_uploadarquivos"]) || $this->w13_uploadarquivos != "")
             $resac = db_query("insert into db_acount values($acount,1383,20004,'".AddSlashes(pg_result($resaco,$conresaco,'w13_uploadarquivos'))."','$this->w13_uploadarquivos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "configdbpref nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w13_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "configdbpref nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w13_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w13_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w13_instit=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($w13_instit));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,9570,'$w13_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,1383,8213,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_liberaatucgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,8214,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_liberapedsenha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,8216,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_permfornsemlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,8215,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_permvarsemlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,8236,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_liberaescritorios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,8235,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_liberaimobiliaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,8372,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_permconscgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,8664,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_aliqissretido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,8766,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_liberaissretido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,9569,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_utilizafolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,9570,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,10725,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_libcertpos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,10726,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_libcarnevariavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,10727,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_libsociosdai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,10728,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_libissprestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,12352,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_emailadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,12612,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_liberalancisssemmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,13345,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_exigecpfcnpjmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,20543,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_exigecpfcnpjinscricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,14401,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_regracnd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,14548,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_permconsservdemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,14585,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_tipocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,14593,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_agrupadebrecibos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,15337,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_msgaviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,19218,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_tipocodigocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1383,20004,'','".AddSlashes(pg_result($resaco,$iresaco,'w13_uploadarquivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from configdbpref
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w13_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w13_instit = $w13_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "configdbpref nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w13_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "configdbpref nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w13_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w13_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:configdbpref";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $w13_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from configdbpref ";
     $sql2 = "";
     if($dbwhere==""){
       if($w13_instit!=null ){
         $sql2 .= " where configdbpref.w13_instit = $w13_instit "; 
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
   function sql_query_file ( $w13_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from configdbpref ";
     $sql2 = "";
     if($dbwhere==""){
       if($w13_instit!=null ){
         $sql2 .= " where configdbpref.w13_instit = $w13_instit "; 
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
